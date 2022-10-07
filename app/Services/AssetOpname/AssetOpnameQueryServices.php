<?php

namespace App\Services\AssetOpname;

use App\Models\LogAssetOpname;

class AssetOpnameQueryServices
{
    public function findById(string $id)
    {
        $data = LogAssetOpname::query()
            ->with(['image'])
            ->where('id', $id)
            ->firstOrFail();

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.opname.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }
}
