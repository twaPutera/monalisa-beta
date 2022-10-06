<?php

namespace App\Services\PemutihanAsset;

use App\Models\PemutihanAsset;

class PemutihanAssetQueryServices
{
    public function findAll()
    {
        return PemutihanAsset::all();
    }
    public function findById(string $id)
    {
        return PemutihanAsset::where('id', $id)->where('status', 'Draft')->first();
    }
    public function findByIdDetail(string $id)
    {
        return PemutihanAsset::where('id', $id)->where('status', 'Publish')->first();
    }
}
