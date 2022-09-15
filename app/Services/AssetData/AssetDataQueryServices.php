<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Models\AssetImage;

class AssetDataQueryServices
{
    public function findById(string $id)
    {
        $data =  AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image'])
            ->where('id', $id)
            ->firstOrFail();

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.image.preview') . '?filename=' . $item->path;
            return $item;
        });

        $data->link_detail = route('admin.listing-asset.detail', $data->id);

        return $data;
    }

    public function findAssetImageById(string $id)
    {
        return AssetImage::query()
            ->where('id', $id)
            ->firstOrFail();
    }
}
