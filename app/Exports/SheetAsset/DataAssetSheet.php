<?php

namespace App\Exports\SheetAsset;

use App\Models\AssetData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $data_asset = AssetData::select(
            'asset_data.kode_asset',
            'asset_data.deskripsi',
            'asset_data.tgl_register',
            'asset_data.tanggal_perolehan',
            'asset_data.nilai_perolehan',
            'asset_data.jenis_penerimaan',
            'asset_data.no_memo_surat',
            'asset_data.no_po',
            'asset_data.no_sp3',
            'asset_data.no_seri',
            // 'asset_data.nilai_buku_asset',
            // 'asset_data.nilai_depresiasi',
            'vendors.kode_vendor',
            'kelas_assets.no_akun',
            'kategori_assets.kode_kategori',
            'satuan_assets.kode_satuan',
            'lokasis.kode_lokasi',
            'asset_data.spesifikasi',
            // 'asset_data.umur_manfaat_fisikal',
            // 'asset_data.umur_manfaat_komersial',
            'asset_data.status_kondisi',
        )
            ->join('vendors', 'vendors.id', '=', 'asset_data.id_vendor')
            ->join('kelas_assets', 'kelas_assets.id', '=', 'asset_data.id_kelas_asset')
            ->join('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset')
            ->join('satuan_assets', 'satuan_assets.id', '=', 'asset_data.id_satuan_asset')
            ->join('lokasis', 'lokasis.id', '=', 'asset_data.id_lokasi')
            ->where('asset_data.id', '0')
            ->orderBy('asset_data.created_at', 'ASC');
        return $data_asset;
    }

    public function title(): string
    {
        return 'Data Asset';
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
            'No Memo Surat',
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
        ];
    }
}
