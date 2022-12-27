<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Helpers\DateIndoHelpers;
use App\Models\LogRequestInventori;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RequestBahanHabisPakaiExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    public function __construct(
        $awal_permintaan = null,
        $akhir_permintaan = null,
        $awal_pengambilan = null,
        $akhir_pengambilan = null,
        $status_permintaan = null
    ) {
        $this->awal_permintaan = $awal_permintaan;
        $this->akhir_permintaan = $akhir_permintaan;
        $this->awal_pengambilan = $awal_pengambilan;
        $this->akhir_pengambilan = $akhir_pengambilan;
        $this->status_permintaan = $status_permintaan;
        $this->number = 0;
    }

    public function query()
    {
        $query = LogRequestInventori::query();
        $query->leftJoin('request_inventories', 'request_inventories.id', 'log_request_inventories.request_inventori_id');
        $query->select([
            'log_request_inventories.*',
            'request_inventories.kode_request',
            'request_inventories.tanggal_pengambilan',
            'request_inventories.created_at as tanggal_permintaan',
            'request_inventories.alasan',
            'request_inventories.no_memo',
        ]);

        if (isset($this->awal_permintaan)) {
            $query->where('request_inventories.created_at', '>=', $this->awal_permintaan . ' 00:00:00');
        }

        if (isset($this->akhir_permintaan)) {
            $query->where('request_inventories.created_at', '<=', $this->akhir_permintaan . ' 23:59:00');
        }

        if (isset($this->awal_pengambilan)) {
            $query->where('tanggal_pengambilan', '>=', $this->awal_pengambilan);
        }

        if (isset($this->akhir_pengambilan)) {
            $query->where('tanggal_pengambilan', '<=', $this->akhir_pengambilan);
        }

        if (isset($this->status_permintaan)) {
            if ($this->status_permintaan != 'all') {
                $query->where('log_request_inventories.status', $this->status_permintaan);
            }
        }

        $query->orderBy('log_request_inventories.created_at', 'ASC');
        return $query;
    }

    public function title(): string
    {
        return 'History Permintaan Bahan Habis Pakai';
    }

    public function map($item): array
    {
        return [
            $this->number += 1,
            DateIndoHelpers::formatDateToIndo(Carbon::parse($item->tanggal_permintaan)->format('Y-m-d')),
            $item->kode_request,
            DateIndoHelpers::formatDateToIndo(Carbon::parse($item->created_at)->format('Y-m-d')),
            DateIndoHelpers::formatDateToIndo($item->tanggal_pengambilan),
            $item->no_memo,
            $item->alasan,
            $item->message,
            $item->status,
            $item->created_by,
        ];
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Permintaan', 'Kode Permintaan', 'Log Terakhir', 'Tanggal Pengambilan', 'No Memo', 'Alasan Permintaan', 'Aktifitas', 'Status', 'Dilakukan Oleh'];
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
