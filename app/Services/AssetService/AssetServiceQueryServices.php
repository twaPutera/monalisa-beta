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
        $data = Service::query()
        ->with(['image'])
        ->where('id', $id)
        ->firstOrFail();
        
        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.service.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }
}
