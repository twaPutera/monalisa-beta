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
                'kode_asset' => 'Asset001',
                'deskripsi' => 'Contoh Asset',
                'tanggal_register' => '22-06-2022',
                'tanggal_perolehan' => '25-08-2022',
                'nilai_perolehan' => '250000',
                'jenis_perolehan' => 'PO',
                'no_memo' => '4123/UP-SU3/MEMO/AK.00/X/2022',
                'no_po' => '4123/UP-SU3/PO/AK.00/X/2022',
                'no_sp3' => '4123/UP-SU3/SP3/AK.00/X/2022',
                'no_seri' => 'Seri 2022',
                'kode_vendor' => 'Vendor001 (Diambil dari Sheet Vendor)',
                'no_akun' => 'Akun001 (Diambil dari Sheet Kode Akun)',
                'kode_jenis' => 'JenisAsset001 (Diambil dari Sheet Kode Jenis Asset)',
                'kode_satuan' => 'Satuan001 (Diambil dari Sheet Kode Lokasi Asset)',
                'kode_lokasi' => 'Lokasi001 (Diambil dari Sheet Kode Lokasi Asset)',
                'spesifikasi' => 'Contoh Spesifikasi Asset',
                'kondisi' => 'bagus',
                'peminjaman' =>  'iya',
                'sparepart' => 'tidak',
                'notif' => '(Hapus Baris Ini Sebelum Mengisi Data)',
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
            'Kode Asset',
            'Deskripsi Asset',
            'Tanggal Register',
            'Tanggal Perolehan',
            'Nilai Perolehan',
            'Jenis Perolehan (PO/Hibah)',
            'No Memorandum',
            'No PO',
            'No SP3',
            'No Seri Asset',
            // 'Nilai Buku Asset',
            // 'Nilai Depresiasi',
            'Kode Vendor Asset',
            'No Akun Asset',
            'Kode Jenis Asset',
            'Kode Satuan Asset',
            'Kode Lokasi Asset',
            'Spesifikasi Asset',
            // 'Umur Manfaat Fisikal',
            // 'Umur Manfaat Komersial',
            'Status Kondisi Asset (bagus/rusak/maintenance/tidak-lengkap)',
            'Status Peminjaman (iya/tidak)',
            'Status Sparepart (iya/tidak)',
        ];
    }
}
