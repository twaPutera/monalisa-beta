<?php

namespace App\Services\KategoriAsset;

use App\Models\KategoriAsset;

class KategoriAssetQueryServices
{
    public function findAll()
    {
        return KategoriAsset::all();
    }

    public function findById(string $id)
    {
        return KategoriAsset::findOrFail($id);
    }
}
