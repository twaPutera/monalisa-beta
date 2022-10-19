<?php

namespace App\Imports\SheetAsset;

use App\Models\Lokasi;
use App\Models\Vendor;
use App\Models\AssetData;
use App\Models\KelasAsset;
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
        $id_vendor = Vendor::where('kode_vendor', $row[10])->first();
        $id_kelas = KelasAsset::where('no_akun', $row[11])->first();
        $id_kategori = KategoriAsset::where('kode_kategori', $row[12])->first();
        $id_satuan = SatuanAsset::where('kode_satuan', $row[13])->first();
        $id_lokasi = Lokasi::where('kode_lokasi', $row[14])->first();
        $user = \Session::get('user');

        $qr_name = 'qr-asset-' . $row[0] . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($row[0], $path);

        $data_asset = AssetData::create([
            'id_vendor' => $id_vendor->id,
            'id_lokasi' => $id_lokasi->id,
            'id_kelas_asset' => $id_kelas->id,
            'id_kategori_asset' => $id_kategori->id,
            'id_satuan_asset' => $id_satuan->id,
            'kode_asset' => $row[0],
            'deskripsi' => $row[1],
            'tanggal_perolehan' => $row[3],
            'nilai_perolehan' => $row[4],
            'jenis_penerimaan' => $row[5],
            'tgl_register' => $row[2],
            'register_oleh' => $user->guid,
            'no_memo_surat' => $row[6],
            'no_po' => $row[7],
            'no_sp3' => $row[8],
            'status_kondisi' => $row[16],
            'no_seri' => $row[9],
            'spesifikasi' => $row[15],
            'nilai_buku_asset' => $row[4],
            'nilai_depresiasi' => DepresiasiHelpers::getNilaiDepresiasi($row[4], $id_kategori->umur_asset),
            // 'umur_manfaat_fisikal' => $row[18],
            'umur_manfaat_komersial' => $id_kategori->umur_asset,
            'created_by' => $user->guid,
            'qr_code' => $qr_name,
        ]);
        return $data_asset;
    }

    public function prepareForValidation($data, $index)
    {
        $data['2'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['2'])->format('Y-m-d');
        $data['3'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['3'])->format('Y-m-d');
        return $data;
    }

    public function rules(): array
    {
        return [
            '0' => 'required|unique:asset_data,kode_asset|max:255',
            '1' => 'required|string|max:255',
            '2' => 'required|date_format:Y-m-d',
            '3' => 'required|date_format:Y-m-d',
            '4' => 'required|numeric',
            '5' => 'required|string|in:PO,Hibah',
            '6' => 'nullable|string|max:50',
            '7' => 'nullable|string|max:50',
            '8' => 'nullable|string|max:50',
            '9' => 'required|string|max:50',
            // '10' => 'required|numeric',
            // '11' => 'required|numeric',
            '10' => 'nullable|exists:vendors,kode_vendor',
            '11' => 'required|exists:kelas_assets,no_akun',
            '12' => 'required|exists:kategori_assets,kode_kategori',
            '13' => 'required|exists:satuan_assets,kode_satuan',
            '14' => 'nullable|exists:lokasis,kode_lokasi',
            '15' => 'required|string',
            // '18' => 'nullable|numeric',
            // '19' => 'nullable|numeric',
            '16' => 'required|string|in:bagus,rusak,maintenance,tidak-lengkap',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Kode Asset',
            '1' => 'Deskripsi Asset',
            '2' => 'Tanggal Register',
            '3' => 'Tanggal Perolehan',
            '4' => 'Nilai Perolehan',
            '5' => 'Jenis Perolehan',
            '6' => 'No Memo',
            '7' => 'No PO',
            '8' => 'No SP3',
            '9' => 'No Seri Asset',
            // '10' => 'Nilai Buku Asset',
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
        ];
    }
}
