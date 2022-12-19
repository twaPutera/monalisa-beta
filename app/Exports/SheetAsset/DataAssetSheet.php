<?php

namespace App\Exports\SheetAsset;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DataAssetSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        $data_asset = collect([
            [
                'no_akun' => 'Akun001 (Diambil dari Sheet Kode Akun)',
                'kode_asset' => 'Asset001',
                'no_urut' => '2022',
                'deskripsi' => 'Contoh Asset',
                // 'tanggal_register' => '22-06-2022',
                'tanggal_perolehan' => '25/08/2022',
                'nilai_perolehan' => '250000',
                'jenis_perolehan' => 'PO',
                // 'nilai_buku_asset' => '12000',
                // 'no_memo' => '4123/UP-SU3/MEMO/AK.00/X/2022',
                'no_po' => '4123/UP-SU3/PO/AK.00/X/2022',
                'no_sp3' => '4123/UP-SU3/SP3/AK.00/X/2022',
                'no_seri' => '1123221',
                'kode_vendor' => 'Vendor001 (Diambil dari Sheet Kode Vendor)',
                'kode_jenis' => 'JenisAsset001 (Diambil dari Sheet Kode Jenis Asset)',
                'kode_satuan' => 'Satuan001 (Diambil dari Sheet Kode Lokasi Asset)',
                'kode_lokasi' => 'Lokasi001 (Diambil dari Sheet Kode Lokasi Asset)',
                'spesifikasi' => 'Contoh Spesifikasi Asset',
                'cost_center' => 'Contoh Cost Center',
                // 'call_center' => '(0362) 22167',
                'kondisi' => 'bagus',
                'peminjaman' =>  'iya',
                'sparepart' => 'tidak',
                'barang_it' => 'IT',
                'notif' => '(Ini Adalah Contoh Pengisian Data, Hapus Baris Ini Sebelum Mengisi Data)',
            ],
        ]);
        return $data_asset;
    }

    public function title(): string
    {
        return 'Data Asset Baru';
    }

    public function headings(): array
    {
        return [
            'Nomor Akun Asset',
            'Kode Asset *',
            'No Urut Asset',
            'Deskripsi Asset *',
            // 'Tanggal Register',
            'Tanggal Perolehan (Format: d/m/Y)*',
            'Nilai Perolehan *',
            'Jenis Perolehan (Opsi: PO/Hibah Eksternal/Hibah Penelitian/Hibah Perorangan) *',
            // 'Nilai Buku Asset *',
            // 'No Memorandum',
            'No PO',
            'No SP3',
            'No Seri Asset',
            // 'Nilai Depresiasi',
            'Kode Vendor Asset',
            'Kode Jenis Asset *',
            'Kode Satuan Asset *',
            'Kode Lokasi Asset',
            'Spesifikasi Asset *',
            'Cost Center/Asset Holder',
            // 'Call Center',
            // 'Umur Manfaat Fisikal',
            // 'Umur Manfaat Komersial',
            'Status Kondisi Asset (Opsi: bagus/rusak/maintenance/tidak-lengkap/pengembangan) *',
            'Status Peminjaman (Opsi: iya/tidak) *',
            'Status Sparepart (Opsi: iya/tidak) *',
            'Status Pemilik Barang (Opsi: IT/Asset) *',
        ];
    }
}
