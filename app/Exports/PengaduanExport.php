<?php

namespace App\Exports;

use App\Models\Pengaduan;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengaduanExport implements FromQuery, WithTitle, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithMapping
{
    public function __construct($tgl_awal = null, $tgl_akhir = null, $lokasi = null, $kategori_asset = null)
    {
        $this->awal = $tgl_awal;
        $this->akhir = $tgl_akhir;
        $this->id_lokasi = $lokasi;
        $this->number = 0;
        $this->id_kategori_asset = $kategori_asset;
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function query()
    {
        $query = Pengaduan::query();
        $query->leftJoin('lokasis', 'pengaduans.id_lokasi', '=', 'lokasis.id');
        $query->leftJoin('asset_data', 'pengaduans.id_asset_data', '=', 'asset_data.id');
        $query->leftJoin('kategori_assets', 'asset_data.id_kategori_asset', '=', 'kategori_assets.id');
        $query->leftJoin('group_kategori_assets', 'kategori_assets.id_group_kategori_asset', '=', 'group_kategori_assets.id');
        $query->select([
            'pengaduans.tanggal_pengaduan',
            'asset_data.deskripsi',
            'group_kategori_assets.nama_group',
            'kategori_assets.nama_kategori',
            'lokasis.nama_lokasi',
            'pengaduans.created_by',
            'pengaduans.catatan_pengaduan',
            'pengaduans.catatan_admin',
            'pengaduans.status_pengaduan'
        ]);
        if (isset($this->id_lokasi) && $this->id_lokasi != 'root') {
            $query->where('pengaduans.id_lokasi', $this->id_lokasi);
        }
        if (isset($this->id_kategori_asset)) {
            $query->where('asset_data.id_kategori_asset', $this->id_kategori_asset);
        }

        if (isset($this->awal)) {
            $query->where('pengaduans.tanggal_pengaduan', '>=', $this->awal);
        }

        if (isset($this->akhir)) {
            $query->where('pengaduans.tanggal_pengaduan', '<=', $this->akhir);
        }
        $query->where('pengaduans.status_pengaduan', 'selesai');
        $query->orderBy('pengaduans.tanggal_pengaduan', 'DESC');
        return $query;
    }

    public function title(): string
    {
        return 'History Pengaduan';
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
            $item->tanggal_pengaduan,
            $item->deskripsi ?? '-',
            $item->nama_group ?? '-',
            $item->nama_kategori ?? '-',
            $item->nama_lokasi ?? '-',
            $name,
            $item->catatan_pengaduan,
            $item->catatan_admin,
            $item->status_pengaduan,
        ];
    }

    public function headings(): array
    {
        return ['No', 'Tanggal Pengaduan Masuk', 'Nama Asset Yang Diadukan', 'Kelompok Asset', 'Jenis Asset', 'Nama Lokasi Yang Diadukan', 'Dilaporkan Oleh', 'Catatan Pengaduan', 'Catatan Admin', 'Status Pengaduan'];
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
