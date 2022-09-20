<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\FileHelpers;
use App\Models\KategoriAsset;
use App\Helpers\QrCodeHelpers;
use App\Helpers\DepresiasiHelpers;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Http\Requests\AssetData\AssetUpdateRequest;

class AssetDataCommandServices
{
    public function store(AssetStoreRequest $request)
    {
        $request->validated();

        $user = \Session::get('user');

        $kategori_asset = KategoriAsset::find($request->id_kategori_asset);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($request->nilai_perolehan, $kategori_asset->umur_asset);

        $qr_name = 'qr-asset-' . $request->kode_asset . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($request->kode_asset, $path);

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
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        $asset->ownership = $request->ownership;
        $asset->tgl_register = date('Y-m-d');
        $asset->register_oleh = $user->guid;
        $asset->no_memo_surat = $request->no_memo_surat;
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        $asset->status_kondisi = $request->status_kondisi;
        $asset->no_seri = $request->no_seri;
        $asset->spesifikasi = $request->spesifikasi;
        $asset->nilai_buku_asset = $request->nilai_perolehan;
        $asset->nilai_depresiasi = $nilai_depresiasi;
        $asset->created_by = $user->guid;
        $asset->qr_code = $qr_name;
        $asset->umur_manfaat_komersial = $kategori_asset->umur_asset;
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

        return $asset;
    }

    public function update(AssetUpdateRequest $request, string $id)
    {
        $request->validated();

        $user = \Session::get('user');

        $asset = AssetData::find($id);
        $asset->deskripsi = $request->deskripsi;
        // $asset->kode_asset = $request->kode_asset;
        $asset->id_vendor = $request->id_vendor;
        $asset->id_lokasi = $request->id_lokasi;
        // $asset->id_kelas_asset = $request->id_kelas_asset;
        // $asset->id_kategori_asset = $request->id_kategori_asset;
        $asset->id_satuan_asset = $request->id_satuan_asset;
        // $asset->tanggal_perolehan = $request->tanggal_perolehan;
        // $asset->nilai_perolehan = $request->nilai_perolehan;
        $asset->jenis_penerimaan = $request->jenis_penerimaan;
        // $asset->ownership = $request->ownership;
        // $asset->tgl_register = date('Y-m-d');
        // $asset->register_oleh = $user->guid;
        $asset->no_memo_surat = $request->no_memo_surat;
        $asset->no_po = $request->no_po;
        $asset->no_sp3 = $request->no_sp3;
        // $asset->status_kondisi = $request->status_kondisi;
        $asset->no_seri = $request->no_seri;
        $asset->spesifikasi = $request->spesifikasi;
        // $asset->nilai_buku_asset = $request->nilai_perolehan;
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

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-'. $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
}
