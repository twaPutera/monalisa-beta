<?php

namespace App\Imports\SheetAsset;

use Carbon\Carbon;
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
        $get_kelas = KelasAsset::where('no_akun', $row[0])->first();
        if ($get_kelas == null) {
            $id_kelas = null;
        } else {
            $id_kelas = $get_kelas->id;
        }
        $qr_name = 'qr-asset-' . $row[1] . '.png';
        $path = storage_path('app/images/qr-code/' . $qr_name);
        $qr_code = QrCodeHelpers::generateQrCode($row[1], $path);

        $get_vendor = Vendor::where('kode_vendor', $row[10])->first();
        if ($get_vendor == null) {
            $id_vendor = null;
        } else {
            $id_vendor = $get_vendor->id;
        }
        $id_kategori = KategoriAsset::where('kode_kategori', $row[11])->first();
        $id_satuan = SatuanAsset::where('kode_satuan', $row[12])->first();
        $get_lokasi = Lokasi::where('kode_lokasi', $row[13])->first();
        if ($get_lokasi == null) {
            $id_lokasi = null;
        } else {
            $id_lokasi = $get_lokasi->id;
        }
        $user = SsoHelpers::getUserLogin();
        $tanggal_perolehan = Carbon::createFromFormat('d/m/Y', $row[4])->format('Y-m-d');

        // Depresiasi
        $tgl_awal_depresiasi = DepresiasiHelpers::getAwalTanggalDepresiasi($tanggal_perolehan);
        $nilai_depresiasi = DepresiasiHelpers::getNilaiDepresiasi($row[5], ($id_kategori->umur_asset * 12));
        $umur_manfaat_komersial = DepresiasiHelpers::generateUmurAsset($tanggal_perolehan, ($id_kategori->umur_asset * 12));

        $data_asset = AssetData::create([
            'id_vendor' => $id_vendor,
            'id_lokasi' => $id_lokasi,
            'id_kelas_asset' => $id_kelas,
            'id_kategori_asset' => $id_kategori->id,
            'id_satuan_asset' => $id_satuan->id,
            'kode_asset' => $row[1],
            'no_urut' => $row[2],
            'deskripsi' => $row[3],
            'tanggal_perolehan' => $tanggal_perolehan,
            'nilai_perolehan' => $row[5],
            'jenis_penerimaan' => $row[6],
            // 'nilai_buku_asset' => $row[7],
            // 'no_memo_surat' => $row[8],
            'no_po' => $row[7],
            'no_sp3' => $row[8],
            'no_seri' => $row[9],
            'tgl_register' => date('Y-m-d'),
            'register_oleh' => config('app.sso_siska') ? $user->guid : $user->id,
            'spesifikasi' => $row[14],
            'cost_center' => $row[15],
            // 'call_center' => $row[17],
            'status_kondisi' => $row[16],
            'is_pinjam' => $row[17] == 'iya' ? 1 : 0,
            'is_sparepart' => $row[18]  == 'iya' ? 1 : 0,
            'nilai_depresiasi' => $nilai_depresiasi,
            'umur_manfaat_komersial' => $umur_manfaat_komersial,
            'created_by' => config('app.sso_siska') ? $user->guid : $user->id,
            'qr_code' => $qr_name,
            'tanggal_awal_depresiasi' => $tgl_awal_depresiasi,
            'tanggal_akhir_depresiasi' => DepresiasiHelpers::getAkhirTanggalDepresiasi($tgl_awal_depresiasi, $id_kategori->umur_asset),
            'is_draft' => 1,
            'is_it' => $row[19] == 'IT' ? 1 : 0,
        ]);
        return $data_asset;
    }

    public function prepareForValidation($data, $index)
    {
        $data['4'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['4'])->format('d/m/Y');
        return $data;
    }

    public function rules(): array
    {
        return [
            '0' => 'nullable|exists:kelas_assets,no_akun',
            '1' => 'required|unique:asset_data,kode_asset|max:255',
            '2' => 'nullable|numeric',
            '3' => 'required|string|max:255',
            '4' => 'required|date_format:d/m/Y',
            '5' => 'required|numeric',
            '6' => 'required|string|in:PO,Hibah Eksternal,Hibah Penelitian,Hibah Perorangan',
            // '7' => 'required|numeric',
            // '8' => 'nullable|string|max:50',
            '7' => 'nullable|string|max:50',
            '8' => 'nullable|string|max:50',
            '9' => 'nullable|string|max:50',
            // '11' => 'required|numeric',
            '10' => 'nullable|exists:vendors,kode_vendor',
            '11' => 'required|exists:kategori_assets,kode_kategori',
            '12' => 'required|exists:satuan_assets,kode_satuan',
            '13' => 'nullable|exists:lokasis,kode_lokasi',
            '14' => 'required|string|max:255',
            '15' => 'nullable|string|max:255',
            // '17' => 'nullable|string|max:50',
            // '18' => 'nullable|numeric',
            // '19' => 'nullable|numeric',
            '16' => 'required|string|in:bagus,rusak,maintenance,tidak-lengkap,pengembangan',
            '17' => 'required|string|in:iya,tidak',
            '18' => 'required|string|in:iya,tidak',
            '19' => 'required|string|in:IT,Asset',
        ];
    }

    public function customValidationAttributes()
    {
        return [
            '0' => 'Nomor Akun Asset',
            '1' => 'Kode Asset',
            '2' => 'No Urut Asset',
            '3' => 'Deskripsi Asset',
            '4' => 'Tanggal Perolehan',
            '5' => 'Nilai Perolehan',
            '6' => 'Jenis Perolehan',
            // '7' => 'Nilai Buku Asset',
            // '8' => 'No Memorandum',
            '7' => 'No PO',
            '8' => 'No SP3',
            '9' => 'No Seri Asset',
            // '11' => 'Nilai Depresiasi',
            '10' => 'Kode Vendor Asset',
            '11' => 'Kode Jenis Asset',
            '12' => 'Kode Satuan Asset',
            '13' => 'Kode Lokasi Asset',
            '14' => 'Spesifikasi Asset',
            '15' => 'Cost Center/Asset Holder',
            // '17' => 'Call Center',
            // '18' => 'Umur Manfaat Fisikal',
            // '19' => 'Umur Manfaat Komersial',
            '16' => 'Status Kondisi Asset',
            '17' => 'Status Peminjaman',
            '18' => 'Status Sparepart',
            '19' => 'Status Pemilik Barang',
        ];
    }
}
