<?php

namespace App\Services\PemutihanAsset;

use App\Models\PemutihanAsset;
use App\Models\DetailPemutihanAsset;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class PemutihanAssetQueryServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
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
            $name = 'Not Found';
            if (config('app.sso_siska')) {
                $user = $data->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($data->created_by);
                $name = isset($user[0]) ? collect($user[0]) : null;
            } else {
                $user = $this->userQueryServices->findById($data->created_by);
                $name = isset($user) ? $user->name : 'Not Found';
            }
        }
        $data->created_by_name = $name;
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
