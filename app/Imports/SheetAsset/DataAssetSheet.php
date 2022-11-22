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
        $get_vendor = Vendor::where('kode_vendor', $row[10])->first();
        if ($get_vendor == null) {
            $id_vendor = null;
        } else {
            $id_vendor = $get_vendor->id;
        }
        $id_kelas = KelasAsset::where('no_akun', $row[11])->first();
        $id_kategori = KategoriAsset::where('kode_kategori', $row[12])->first();
        $id_satuan = SatuanAsset::where('kode_satuan', $row[13])->first();
        $id_lokasi = Lokasi::where('kode_lokasi', $row[14])->first();
        $user = SsoHelpers::getUserLogin();

        $qr_name = 'qr-asset-' . $row[0] . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($row[0], $path);

        // Depresiasi
        $tgl_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($row[2]);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($row[3], ($id_kategori->umur_asset * 12));
        $umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($row[2], ($id_kategori->umur_asset * 12));


        $data_asset = AssetData::create([
            'id_vendor' => $id_vendor,
            'id_lokasi' => $id_lokasi->id,
            'id_kelas_asset' => $id_kelas->id,
            'id_kategori_asset' => $id_kategori->id,
            'id_satuan_asset' => $id_satuan->id,
            'kode_asset' => $row[0],
            'deskripsi' => $row[1],
            'tanggal_perolehan' => $row[2],
            'nilai_perolehan' => $row[3],
            'jenis_penerimaan' => $row[4],
            'tgl_register' => date('Y-m-d'),
            'register_oleh' => config('app.sso_siska') ? $user->guid : $user->id,
            'no_memo_surat' => $row[5],
            'no_po' => $row[6],
            'no_sp3' => $row[7],
            'nilai_buku_asset' => $row[9],
            'status_kondisi' => $row[16],
            'no_seri' => $row[8],
            'spesifikasi' => $row[15],
            'is_pinjam' => $row[17] == 'iya' ? 1 : 0,
            'is_sparepart' => $row[18]  == 'iya' ? 1 : 0,
            'nilai_depresiasi' => $nilai_depresiasi,
            'umur_manfaat_komersial' => $umur_manfaat_komersial,
            'created_by' => config('app.sso_siska') ? $user->guid : $user->id,
            'qr_code' => $qr_name,
            'tanggal_awal_depresiasi' => $tgl_awal_depresiasi,
            'tanggal_akhir_depresiasi' => DepresiasiHelpers::getAkhirTanggalDepresiasi($tgl_awal_depresiasi, $id_kategori->umur_asset),
        ]);
        return $data_asset;
    }

    public function prepareForValidation($data, $index)
    {
        $data['2'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['2'])->format('Y-m-d');
        return $data;
    }

    public function rules(): array
    {
        return [
            '0' => 'required|unique:asset_data,kode_asset|max:255',
            '1' => 'required|string|max:255',
            '2' => 'required|date_format:Y-m-d',
            '3' => 'required|numeric',
            '4' => 'required|string|in:PO,Hibah',
            '5' => 'nullable|string|max:50',
            '6' => 'nullable|string|max:50',
            '7' => 'nullable|string|max:50',
            '8' => 'required|string|max:50',
            '9' => 'required|numeric',
            // '11' => 'required|numeric',
            '10' => 'nullable|exists:vendors,kode_vendor',
            '11' => 'required|exists:kelas_assets,no_akun',
            '12' => 'required|exists:kategori_assets,kode_kategori',
            '13' => 'required|exists:satuan_assets,kode_satuan',
            '14' => 'nullable|exists:lokasis,kode_lokasi',
            '15' => 'required|string',
            // '18' => 'nullable|numeric',
            // '19' => 'nullable|numeric',
            '16' => 'required|string|in:bagus,rusak,maintenance,tidak-lengkap,pengembangan',
            '17' => 'required|string|in:iya,tidak',
            '18' => 'required|string|in:iya,tidak',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Kode Asset',
            '1' => 'Deskripsi Asset',
            '2' => 'Tanggal Perolehan',
            '3' => 'Nilai Perolehan',
            '4' => 'Jenis Perolehan',
            '5' => 'No Memo',
            '6' => 'No PO',
            '7' => 'No SP3',
            '8' => 'No Seri Asset',
            '9' => 'Nilai Buku Asset',
            // '11' => 'Nilai Depresiasi',
            '10' => 'Kode Vendor',
            '11' => 'No Akun',
            '12' => 'Kode Jenis Asset',
            '13' => 'Kode Satuan',
            '14' => 'Kode Lokasi',
            '15' => 'Spesifikasi',
            // '18' => 'Umur Manfaat Fisikal',
            // '19' => 'Umur Manfaat Komersial',
            '16' => 'Status Kondisi Asset',
            '17' => 'Status Peminjaman',
            '18' => 'Status Sparepart',
        ];
    }
}
