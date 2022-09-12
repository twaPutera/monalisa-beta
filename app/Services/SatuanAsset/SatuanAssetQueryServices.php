<?php

namespace App\Services\SatuanAsset;

use App\Models\SatuanAsset;

class SatuanAssetQueryServices
{
    public function findAll()
    {
        return SatuanAsset::all();
    }

    public function findById(string $id)
    {
        return SatuanAsset::findOrFail($id);
    }
}
