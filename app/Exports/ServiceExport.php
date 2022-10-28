<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    public function __construct($tgl_awal = null, $tgl_akhir = null, $lokasi = null)
    {
        $this->awal = $tgl_awal;
        $this->akhir = $tgl_akhir;
        $this->id_lokasi = $lokasi;
        $this->number = 0;
    }

    public function query()
    {
        $query = Service::query();
        $query->join('kategori_services', 'services.id_kategori_service', '=', 'kategori_services.id');
        $query->join('detail_services', 'detail_services.id_service', '=', 'services.id');
        $query->join('asset_data', 'detail_services.id_asset_data', '=', 'asset_data.id');
        $query->join('kategori_assets', 'asset_data.id_kategori_asset', '=', 'kategori_assets.id');
        $query->join('group_kategori_assets', 'kategori_assets.id_group_kategori_asset', '=', 'group_kategori_assets.id');
        $query->join('lokasis', 'detail_services.id_lokasi', '=', 'lokasis.id');
        $query->select([
            'services.tanggal_mulai',
            'services.tanggal_selesai',
            'asset_data.kode_asset',
            'asset_data.deskripsi',
            'kategori_assets.nama_kategori',
            'lokasis.nama_lokasi',
            'asset_data.status_kondisi',
            'group_kategori_assets.nama_group',
            'detail_services.permasalahan',
            'detail_services.tindakan',
            'detail_services.catatan',
            'services.keterangan',
            'services.status_service'
        ]);

        if (isset($this->id_lokasi)) {
            $query->where('detail_services.id_lokasi', $this->id_lokasi);
        }
        if (isset($this->awal)) {
            $query->where('services.tanggal_selesai', '>=', $this->awal);
        }

        if (isset($this->akhir)) {
            $query->where('services.tanggal_selesai', '<=', $this->akhir);
        }
        $query->where('services.status_service', 'selesai');
        $query->orderBy('services.created_at', 'DESC');
        return $query;
    }

    public function title(): string
    {
        return 'History Service';
    }

    public function map($item): array
    {
        return [
            $this->number += 1,
            $item->tanggal_mulai,
            $item->tanggal_selesai,
            $item->kode_asset,
            $item->deskripsi,
            $item->nama_kategori,
            $item->nama_lokasi,
            $item->status_kondisi,
            $item->nama_group,
            $item->permasalahan,
            $item->tindakan,
            $item->catatan,
            $item->keterangan,
            $item->status_service
        ];
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Mulai', 'Tanggal Selesai', 'Kode Asset', 'Deskripsi Asset', 'Jenis Asset', 'Lokasi Asset', 'Status Kondisi Asset', 'Kelompok Asset', 'Permasalahan', 'Tindakan', 'Catatan', 'Keterangan Service', 'Status Service'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'alignment' => ['horizontal' => 'center', 'vertical' => 'center']],
        ];
    }
    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function (AfterSheet $event) {
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();
                $lastCell = $highestColumn . $highestRow;
                $rangeCell = 'A1:' . $lastCell;
                $event->sheet->getDelegate()->getStyle($rangeCell)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            },
        ];
    }
}
