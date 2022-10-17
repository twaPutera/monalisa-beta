<?php

namespace App\Services\Keluhan;

use App\Models\Pengaduan;
use App\Services\UserSso\UserSsoQueryServices;
use Exception;

class KeluhanQueryServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }
    
    public function findById(string $id)
    {
        $data = Pengaduan::query()
            ->with(['image', 'asset_data', 'asset_data.lokasi', 'asset_data.kategori_asset', 'asset_data.kategori_asset.group_kategori_asset'])
            ->where('id', $id)
            ->firstOrFail();

        if (isset($data->created_by)) {
            $user = $this->userSsoQueryServices->getUserByGuid($data->created_by);
        }
        $data->created_by_name = $user == null ? 'Tidak ada' : $user[0]['nama'];

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.keluhan.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }
}
