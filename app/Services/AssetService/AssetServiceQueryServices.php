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

    public function findLastestLogByAssetId(string $id)
    {
        $services = Service::query()->with(['detail_service'])
            ->whereHas('detail_service', function ($query) use ($id) {
                $query->where('id_asset_data', $id);
            })
            ->where('status_service', 'done')
            ->orderby('created_at', 'desc')
            ->first();

        return $services;
    }
}
