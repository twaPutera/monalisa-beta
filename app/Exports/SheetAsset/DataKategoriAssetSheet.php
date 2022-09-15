<?php

namespace App\Exports\SheetAsset;

use App\Models\KategoriAsset;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataKategoriAssetSheet implements FromQuery, WithTitle, WithHeadings
{

    public function query()
    {
        $kategori_asset = KategoriAsset::select('group_kategori_assets.kode_group', 'group_kategori_assets.nama_group', 'kategori_assets.kode_kategori', 'kategori_assets.nama_kategori')->join('group_kategori_assets', 'group_kategori_assets.id', '=', 'kategori_assets.id_group_kategori_asset')->orderBy('kategori_assets.created_at', 'ASC');
        return $kategori_asset;
    }

    public function title(): string
    {
        return 'Kategori Asset';
    }

    public function headings(): array
    {
        return ['Kode Group Asset', 'Nama Group Asset', 'Kode Kategori Asset', 'Nama Kategori Asset'];
    }
}
