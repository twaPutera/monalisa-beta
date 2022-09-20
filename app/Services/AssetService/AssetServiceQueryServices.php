<?php

namespace App\Services\AssetService;

use App\Models\Service;

class AssetServiceQueryServices
{
    public function findAll()
    {
        return Service::all();
    }

    public function findById(string $id)
    {
        return Service::findOrFail($id);
    }
}
