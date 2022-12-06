<?php

namespace App\Services\AssetData;

use App\Models\LogAsset;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\KategoriAsset;
use App\Helpers\QrCodeHelpers;
use App\Helpers\DepresiasiHelpers;
use App\Helpers\SistemConfigHelpers;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Http\Requests\AssetData\AssetUpdateDraftRequest;
use App\Http\Requests\AssetData\AssetDataPublishRequest;
use App\Http\Requests\AssetData\AssetUpdateRequest;
use App\Services\SistemConfig\SistemConfigQueryServices;

class AssetDataCommandServices
{
    protected $sistemConfigServices;

    public function __construct()
    {
        $this->sistemConfigServices = new SistemConfigQueryServices();
    }

    public function store(AssetStoreRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $kategori_asset = KategoriAsset::find($request->id_kategori_asset);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($request->nilai_perolehan, ($kategori_asset->umur_asset * 12));

        $qr_name = 'qr-asset-' . $request->kode_asset . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($request->kode_asset, $path);

        $min_asset_value = SistemConfigHelpers::get('min_asset_value');

        $asset = new AssetData();
        $asset->deskripsi = $request->deskripsi;
        $asset->kode_asset = $request->kode_asset;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        $asset->id_kelas_asset = $request->id_kelas_asset;
        $asset->id_kategori_asset = $request->id_kategori_asset;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        $asset->tanggal_perolehan = $request->tanggal_perolehan;
        $asset->nilai_perolehan = $request->nilai_perolehan;
        $asset->nilai_buku_asset = $request->nilai_buku_asset;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->ownership = $request->ownership;
        $asset->tgl_register = date('Y-m-d');
        $asset->register_oleh = config('app.sso_siska') ? $user->guid : $user->id;
        $asset->no_memo_surat = $request->no_memo_surat;
        $asset->id_surat_memo_andin = $request->id_surat_memo_andin;
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->no_urut = $request->no_urut;
        $asset->cost_center = $request->cost_center;
        // $asset->call_center = $request->call_center;
        $asset->status_kondisi = $request->status_kondisi;
        $asset->status_akunting = $request->status_akunting;
        $asset->no_seri = $request->no_seri;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->nilai_depresiasi = $nilai_depresiasi;
        $asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $asset->qr_code = $qr_name;
        $asset->umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($request->tanggal_perolehan, ($kategori_asset->umur_asset * 12));
        $asset->is_sparepart = isset($request->is_sparepart) ? $request->is_sparepart : '0';
        $asset->is_pinjam = isset($request->is_pinjam) ? $request->is_pinjam : '0';
        $asset->is_it = isset($request->is_it) ? $request->is_it : '0';
        $asset->is_inventaris = $min_asset_value > $request->nilai_perolehan ? '1' : '0';
        $asset->is_draft = '1';
        $asset->tanggal_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($request->tanggal_perolehan);
        $asset->tanggal_akhir_depresiasi = DepresiasiHelpers::getAkhirTanggalDepresiasi($asset->tanggal_awal_depresiasi, $kategori_asset->umur_asset);
        $asset->save();

        if ($request->hasFile('gambar_asset')) {
            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $path = storage_path('app/images/asset');
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        $message_log = 'Asset baru dengan kode ' . $asset->kode_asset . ' telah ditambahkan';
        $this->insertLogAsset($asset->id, $message_log);

        if (isset($request->asal_asset)) {
            $asset_asal = AssetData::find($request->asal_asset);
            if (isset($asset_asal)) {
                $message_log = 'Asset ini diambil dari asset ' . $asset_asal->deskripsi . ' dengan kode asset ' . $asset_asal->kode_asset;
                if ($asset_asal->status_kondisi != 'rusak') {
                    $asset_asal->status_kondisi = 'tidak-lengkap';
                    $asset_asal->save();
                }

                // * insert log asset
                $this->insertLogAsset($asset->id, $message_log);

                // * store log ke asal asset
                $message_log_asal_asset = 'Sparepart Asset ' . $asset_asal->deskripsi . ' dengan kode asset ' . $asset_asal->kode_asset . ' telah diambil menjadi sparepart asset ' . $asset->deskripsi . ' dengan kode asset ' . $asset->kode_asset;
                $this->insertLogAsset($asset_asal->id, $message_log_asal_asset);
            }
        }

        return $asset;
    }

    public function update(AssetUpdateRequest $request, string $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $asset = AssetData::find($id);
        $asset->deskripsi = $request->deskripsi;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->no_memo_surat = $request->no_memo_surat;
        $asset->id_surat_memo_andin = $request->id_surat_memo_andin;
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->no_seri = $request->no_seri;
        $asset->no_urut = $request->no_urut;
        $asset->cost_center = $request->cost_center;
        // $asset->call_center = $request->call_center;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->is_sparepart = isset($request->is_sparepart) ? $request->is_sparepart : '0';
        $asset->is_pinjam = isset($request->is_pinjam) ? $request->is_pinjam : '0';
        $asset->is_it = isset($request->is_it) ? $request->is_it : '0';
        $asset->save();

        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');
            if (isset($asset->image[0])) {
                $pathOld = $path . '/' . $asset->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        return $asset;
    }

    public function updateDraft(AssetUpdateDraftRequest $request, string $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $asset = AssetData::find($id);
        $asset->deskripsi = $request->deskripsi;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->no_memo_surat = $request->no_memo_surat;
        $asset->id_surat_memo_andin = $request->id_surat_memo_andin;
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->no_seri = $request->no_seri;
        $asset->no_urut = $request->no_urut;
        $asset->cost_center = $request->cost_center;
        // $asset->call_center = $request->call_center;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->is_sparepart = isset($request->is_sparepart) ? $request->is_sparepart : '0';
        $asset->is_pinjam = isset($request->is_pinjam) ? $request->is_pinjam : '0';
        $asset->is_it = isset($request->is_it) ? $request->is_it : '0';
        $asset->kode_asset = $request->kode_asset;
        $asset->id_kelas_asset = $request->id_kelas_asset;
        $asset->id_kategori_asset = $request->id_kategori_asset;
        $asset->tanggal_perolehan = $request->tanggal_perolehan;
        $asset->nilai_perolehan = $request->nilai_perolehan;
        $asset->nilai_buku_asset = $request->nilai_buku_asset;
        $asset->ownership = $request->ownership;
        $asset->tgl_register = date('Y-m-d');
        $asset->status_kondisi = $request->status_kondisi;
        $asset->nilai_buku_asset = $request->nilai_perolehan;

        if ($request->kode_asset != $asset->kode_asset) {
            \File::delete(storage_path('app/images/qr-code/' . $asset->qr_code));
            $qr_name = 'qr-asset-' . $request->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($request->kode_asset, $path);
            $asset->qr_code = $qr_name;
        }

        if ($request->tanggal_perolehan != $asset->tanggal_perolehan || $request->id_kategori_asset != $asset->id_kategori_asset || $request->nilai_buku_asset != $asset->nilai_buku_asset) {
            $kategori_asset = KategoriAsset::find($request->id_kategori_asset);
            $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($request->nilai_perolehan, ($kategori_asset->umur_asset * 12));
            $asset->nilai_depresiasi = $nilai_depresiasi;
            $asset->umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($request->tanggal_perolehan, ($kategori_asset->umur_asset * 12));
            $asset->tanggal_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($request->tanggal_perolehan);
            $asset->tanggal_akhir_depresiasi = DepresiasiHelpers::getAkhirTanggalDepresiasi($asset->tanggal_awal_depresiasi, $kategori_asset->umur_asset);
        }

        $asset->save();

        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');
            if (isset($asset->image[0])) {
                $pathOld = $path . '/' . $asset->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset);
            $asset_images->imageable_id = $asset->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }

        return $asset;
    }

    public function insertLogAsset($asset_id, $log)
    {
        $user = SsoHelpers::getUserLogin();

        $log_asset = new LogAsset();
        $log_asset->asset_id = $asset_id;
        $log_asset->log = $log;
        $log_asset->created_by = $user->name;
        $log_asset->save();
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }

    public function delete($id)
    {
        $asset = AssetData::find($id);

        if ($asset->is_draft == '0') {
            throw new \Exception('Tidak bisa menghapus asset yang sudah di publish');
        }

        $asset->delete();
    }

    public function publishAssetMany(AssetDataPublishRequest $request)
    {
        $id_asset = json_decode($request->json_id_asset_selected);

        foreach ($id_asset as $id) {
            $asset = AssetData::find($id);
            $asset->is_draft = '0';
            $asset->save();
        }

        return $asset;
    }

    public function publishAllDraftAsset()
    {
        $query = AssetData::where('is_pemutihan', '0')->where('is_draft', '1')->get();
        foreach ($query as $data) {
            $data->is_draft = '0';
            $data->save();
        }
        return $query;
    }
}
