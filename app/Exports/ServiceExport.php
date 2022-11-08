<?php

namespace App\Exports;

use App\Models\LogServiceAsset;
use App\Services\User\UserQueryServices;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Services\UserSso\UserSsoQueryServices;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiceExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    public function __construct(
        $tgl_awal = null,
        $tgl_akhir = null,
        $lokasi = null,
        $kategori_asset = null,
        $status_servce = null,
        $asset_data = null,
    ) {
        $this->awal = $tgl_awal;
        $this->akhir = $tgl_akhir;
        $this->id_lokasi = $lokasi;
        $this->id_kategori_asset = $kategori_asset;
        $this->id_asset_data = $asset_data;
        $this->status_service = $status_servce;
        $this->number = 0;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function query()
    {
        $query = LogServiceAsset::query();
        $query->join('services', 'log_service_assets.id_service', '=', 'services.id');
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
            'log_service_assets.status',
            'log_service_assets.created_at as log_terakhir',
            'log_service_assets.message_log',
            'log_service_assets.created_by',
        ]);

        if (isset($this->status_service)) {
            if ($this->status_service != 'all') {
                $query->where('log_service_assets.status', $this->status_service);
            }
        }

        if (isset($this->id_asset_data)) {
            $query->where('detail_services.id_asset_data', $this->id_asset_data);
        }

        if (isset($this->id_lokasi)) {
            $query->where('detail_services.id_lokasi', $this->id_lokasi);
        }

        if (isset($this->id_kategori_asset)) {
            $query->where('asset_data.id_kategori_asset', $this->id_kategori_asset);
        }

        if (isset($this->awal)) {
            $query->where('services.tanggal_mulai', '>=', $this->awal);
        }

        if (isset($this->akhir)) {
            $query->where('services.tanggal_selesai', '<=', $this->akhir);
        }
        $query->orderBy('log_service_assets.created_at', 'DESC');
        return $query;
    }

    public function title(): string
    {
        return 'History Service';
    }

    public function map($item): array
    {
        $name = '-';
        if (config('app.sso_siska')) {
            $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
            $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
        } else {
            $user = $item->created_by == null ? null : $this->userQueryServices->findById($item->created_by);
            $name = isset($user) ? $user->name : 'Not Found';
        }
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
            $item->status,
            $item->log_terakhir,
            $item->message_log,
            $name,
        ];
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Mulai', 'Tanggal Selesai', 'Kode Asset', 'Deskripsi Asset', 'Jenis Asset', 'Lokasi Asset', 'Status Kondisi Asset', 'Kelompok Asset', 'Permasalahan', 'Tindakan', 'Catatan', 'Keterangan Service', 'Status Service', 'Log Terakhir', 'Aktifitas', 'Dilakukan Oleh'];
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
