<?php

namespace App\Services\PemutihanAsset;

use App\Models\PemutihanAsset;
use App\Models\DetailPemutihanAsset;
use App\Services\UserSso\UserSsoQueryServices;

class PemutihanAssetQueryServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }
    public function findAll()
    {
        return PemutihanAsset::all();
    }

    public function findById(string $id, string $status = null)
    {
        $user = null;
        if (! empty($status)) {
            $data = PemutihanAsset::query()
                ->with(['approval', 'detail_pemutihan_asset', 'detail_pemutihan_asset.asset_data', 'detail_pemutihan_asset.asset_data.lokasi'])
                ->where('id', $id)
                ->where('status', $status)->first();
        } else {
            $data = PemutihanAsset::query()
                ->with(['approval', 'detail_pemutihan_asset', 'detail_pemutihan_asset.asset_data', 'detail_pemutihan_asset.asset_data.lokasi'])
                ->where('id', $id)->first();
        }
        if (isset($data->created_by)) {
            $user = $this->userSsoQueryServices->getUserByGuid($data->created_by);
        }
        $data->created_by_name = $user == null ? 'Tidak ada' : $user[0]['nama'];
        return $data;
    }

    public function findDetailById(string $id)
    {
        $data = DetailPemutihanAsset::query()
            ->with(['image'])
            ->where('id', $id)
            ->firstOrFail();

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.pemutihan.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }
}
