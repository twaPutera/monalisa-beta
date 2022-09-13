<?php

namespace App\Services\GroupKategoriAsset;

use App\Models\GroupKategoriAsset;

class GroupKategoriAssetQueryServices
{
    public function findAll()
    {
        return GroupKategoriAsset::query()->orderby('nama_group', 'asc')->get();
    }

    public function findById(string $id)
    {
        return GroupKategoriAsset::findOrFail($id);
    }

    public function findByKodeGroup(string $kodeGroup)
    {
        return GroupKategoriAsset::where('kode_group', $kodeGroup)->first();
    }
}
