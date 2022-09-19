<?php

namespace App\Imports\SheetAsset;

use App\Models\Lokasi;
use App\Models\Vendor;
use App\Models\AssetData;
use App\Models\KelasAsset;
use App\Models\SatuanAsset;
use App\Models\KategoriAsset;
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
        $id_vendor = Vendor::where('kode_vendor', $row[12])->first();
        $id_kelas = KelasAsset::where('no_akun', $row[13])->first();
        $id_kategori = KategoriAsset::where('kode_kategori', $row[14])->first();
        $id_satuan = SatuanAsset::where('kode_satuan', $row[15])->first();
        $id_lokasi = Lokasi::where('kode_lokasi', $row[16])->first();
        $user = \Session::get('user');

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
            'status_kondisi' => $row[20] == 'Baik' ? 1 : 0,
            'no_seri' => $row[9],
            'spesifikasi' => $row[17],
            'nilai_buku_asset' => $row[10],
            'nilai_depresiasi' => $row[11],
            'umur_manfaat_fisikal' => $row[18],
            'umur_manfaat_komersial' => $row[19],
            'created_by' => $user->guid,
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
            '5' => 'required|string|in:PO,Hibah,Pengadaan',
            '6' => 'nullable|string|max:50',
            '7' => 'nullable|string|max:50',
            '8' => 'nullable|string|max:50',
            '9' => 'required|string|max:50',
            '10' => 'required|numeric',
            '11' => 'required|numeric',
            '12' => 'nullable|exists:vendors,kode_vendor',
            '13' => 'required|exists:kelas_assets,no_akun',
            '14' => 'required|exists:kategori_assets,kode_kategori',
            '15' => 'required|exists:satuan_assets,kode_satuan',
            '16' => 'nullable|exists:lokasis,kode_lokasi',
            '17' => 'required|string',
            '18' => 'nullable|numeric',
            '19' => 'nullable|numeric',
            '20' => 'required|string|in:Baik,Rusak',
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
            '10' => 'Nilai Buku Asset',
            '11' => 'Nilai Depresiasi',
            '12' => 'Kode Vendor',
            '13' => 'No Akun',
            '14' => 'Kode Kategori',
            '15' => 'Kode Satuan',
            '16' => 'Kode Lokasi',
            '17' => 'Spesifikasi',
            '18' => 'Umur Manfaat Fisikal',
            '19' => 'Umur Manfaat Komersial',
            '20' => 'Status Kondisi Asset',
        ];
    }
}
