<?php

namespace App\Imports\SheetAsset;

use App\Models\Lokasi;
use App\Models\Vendor;
use App\Models\AssetData;
use App\Models\KelasAsset;
use App\Helpers\SsoHelpers;
use App\Models\SatuanAsset;
use App\Models\KategoriAsset;
use App\Helpers\QrCodeHelpers;
use App\Helpers\DepresiasiHelpers;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataAssetSheet implements ToModel, WithStartRow, WithValidation
{
    use Importable;
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        $get_kelas = KelasAsset::where('no_akun', $row[0])->first();
        if ($get_kelas == null) {
            $id_kelas = null;
        } else {
            $id_kelas = $get_kelas->id;
        }
        $qr_name = 'qr-asset-' . $row[1] . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($row[1], $path);

        $get_vendor = Vendor::where('kode_vendor', $row[12])->first();
        if ($get_vendor == null) {
            $id_vendor = null;
        } else {
            $id_vendor = $get_vendor->id;
        }
        $id_kategori = KategoriAsset::where('kode_kategori', $row[13])->first();
        $id_satuan = SatuanAsset::where('kode_satuan', $row[14])->first();
        $get_lokasi = Lokasi::where('kode_lokasi', $row[15])->first();
        if ($get_lokasi == null) {
            $id_lokasi = null;
        } else {
            $id_lokasi = $get_lokasi->id;
        }
        $user = SsoHelpers::getUserLogin();
        $tanggal_perolehan = Carbon::createFromFormat('d/m/Y', $row[3])->format('Y-m-d');

        // Depresiasi
        $tgl_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($tanggal_perolehan);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($row[4], ($id_kategori->umur_asset * 12));
        $umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($tanggal_perolehan, ($id_kategori->umur_asset * 12));


        $data_asset = AssetData::create([
            'id_vendor' => $id_vendor,
            'id_lokasi' => $id_lokasi,
            'id_kelas_asset' => $id_kelas,
            'id_kategori_asset' => $id_kategori->id,
            'id_satuan_asset' => $id_satuan->id,
            'kode_asset' => $row[1],
            'deskripsi' => $row[2],
            'tanggal_perolehan' => $tanggal_perolehan,
            'nilai_perolehan' => $row[4],
            'jenis_penerimaan' => $row[5],
            'nilai_buku_asset' => $row[6],
            'no_memo_surat' => $row[7],
            'no_po' => $row[8],
            'no_sp3' => $row[9],
            'no_urut' => $row[10],
            'no_seri' => $row[11],
            'tgl_register' => date('Y-m-d'),
            'register_oleh' => config('app.sso_siska') ? $user->guid : $user->id,
            'spesifikasi' => $row[16],
            'cost_center' => $row[17],
            'status_kondisi' => $row[18],
            'is_pinjam' => $row[19] == 'iya' ? 1 : 0,
            'is_sparepart' => $row[20]  == 'iya' ? 1 : 0,
            'nilai_depresiasi' => $nilai_depresiasi,
            'umur_manfaat_komersial' => $umur_manfaat_komersial,
            'created_by' => config('app.sso_siska') ? $user->guid : $user->id,
            'qr_code' => $qr_name,
            'tanggal_awal_depresiasi' => $tgl_awal_depresiasi,
            'tanggal_akhir_depresiasi' => DepresiasiHelpers::getAkhirTanggalDepresiasi($tgl_awal_depresiasi, $id_kategori->umur_asset),
            'is_draft' => 1,
        ]);
        return $data_asset;
    }

    public function prepareForValidation($data, $index)
    {
        $data['3'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['3'])->format('d/m/Y');
        return $data;
    }

    public function rules(): array
    {
        return [
            '0' => 'nullable|exists:kelas_assets,no_akun',
            '1' => 'required|unique:asset_data,kode_asset|max:255',
            '2' => 'required|string|max:255',
            '3' => 'required|date_format:d/m/Y',
            '4' => 'required|numeric',
            '5' => 'required|string|in:PO,Hibah',
            '6' => 'required|numeric',
            '7' => 'nullable|string|max:50',
            '8' => 'nullable|string|max:50',
            '9' => 'nullable|string|max:50',
            '10' => 'nullable|string|max:50',
            '11' => 'nullable|string|max:50',
            // '11' => 'required|numeric',
            '12' => 'nullable|exists:vendors,kode_vendor',
            '13' => 'required|exists:kategori_assets,kode_kategori',
            '14' => 'required|exists:satuan_assets,kode_satuan',
            '15' => 'nullable|exists:lokasis,kode_lokasi',
            '16' => 'required|string|max:255',
            '17' => 'nullable|string|max:255',
            // '18' => 'nullable|numeric',
            // '19' => 'nullable|numeric',
            '18' => 'required|string|in:bagus,rusak,maintenance,tidak-lengkap,pengembangan',
            '19' => 'required|string|in:iya,tidak',
            '20' => 'required|string|in:iya,tidak',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Nomor Akun Asset',
            '1' => 'Kode Asset',
            '2' => 'Deskripsi Asset',
            '3' => 'Tanggal Perolehan',
            '4' => 'Nilai Perolehan',
            '5' => 'Jenis Perolehan',
            '6' => 'Nilai Buku Asset',
            '7' => 'No Memorandum',
            '8' => 'No PO',
            '9' => 'No SP3',
            '10' => 'No Urut Asset',
            '11' => 'No Seri Asset',
            // '11' => 'Nilai Depresiasi',
            '12' => 'Kode Vendor Asset',
            '13' => 'Kode Jenis Asset',
            '14' => 'Kode Satuan Asset',
            '15' => 'Kode Lokasi Asset',
            '16' => 'Spesifikasi Asset',
            '17' => 'Cost Center/Asset Holder',
            // '18' => 'Umur Manfaat Fisikal',
            // '19' => 'Umur Manfaat Komersial',
            '18' => 'Status Kondisi Asset',
            '19' => 'Status Peminjaman',
            '20' => 'Status Sparepart',
        ];
    }
}
