<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PengaduanExport implements FromQuery, WithHeadings
{
    public function __construct($tgl_awal = null, $tgl_akhir = null, $lokasi = null)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->lokasi = $lokasi;
    }

    public function query()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'History Service';
    }


    public function headings(): array
    {
        return ['Tanggal Mulai', 'Tanggal Selesai', 'Kode Asset', 'Deskripsi Asset', 'Jenis Asset', 'Tipe', 'Status Kondisi Asset', 'Kelompok Asset', 'Permasalahan', 'Tindakan', 'Catatan', 'Keteranbgan Service', 'Status Service'];
    }
}
