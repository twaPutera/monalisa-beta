<?php

namespace App\Services\KelasAsset;

use App\Models\KelasAsset;

class KelasAssetQueryServices
{
    public function findAll()
    {
        return KelasAsset::all();
    }

    public function findById(string $id)
    {
        return KelasAsset::findOrFail($id);
    }
}
