<?php

namespace App\Exports\SheetAsset;

use App\Models\AssetData;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataAssetSheet implements FromQuery, WithTitle, WithHeadings
{
    public function query()
    {
        $data_asset = AssetData::select(
            'asset_data.kode_asset',
            'asset_data.deskripsi',
            'asset_data.tgl_register',
            'asset_data.register_oleh',
            'asset_data.tanggal_perolehan',
            'asset_data.nilai_perolehan',
            'asset_data.jenis_penerimaan',
            'asset_data.no_memo_surat',
            'asset_data.no_po',
            'asset_data.no_sp3',
            'asset_data.no_seri',
            'asset_data.nilai_buku_asset',
            'asset_data.nilai_depresiasi',
            'asset_data.ownership',
            'vendors.kode_vendor',
            'kelas_assets.no_akun',
            'kategori_assets.kode_kategori',
            'satuan_assets.kode_satuan',
            'lokasis.kode_lokasi',
            'asset_data.spesifikasi',
            'asset_data.status_kondisi',
        )
            ->join('vendors', 'vendors.id', '=', 'asset_data.id_vendor')
            ->join('kelas_assets', 'kelas_assets.id', '=', 'asset_data.id_kelas_asset')
            ->join('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset')
            ->join('satuan_assets', 'satuan_assets.id', '=', 'asset_data.id_satuan_asset')
            ->join('lokasis', 'lokasis.id', '=', 'asset_data.id_lokasi')
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
            'Diregister Oleh',
            'Tanggal Perolehan',
            'Nilai Perolehan',
            'Jenis Penerimaan',
            'No Memo Surat',
            'No PO',
            'No SP3',
            'No Seri Asset',
            'Nilai Buku Asset',
            'Nilai Depresiasi',
            'Ownership Asset',
            'Kode Vendor Asset',
            'No Akun Asset',
            'Kode Kategori Asset',
            'Kode Satuan Asset',
            'Kode Lokasi Asset',
            'Spesifikasi Asset',
            'Status Kondisi Asset'
        ];
    }
}
