<?php

namespace App\Services\PemutihanAsset;

use App\Models\PemutihanAsset;
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

    public function findById(string $id)
    {
        $user = null;
        $data = PemutihanAsset::query()
            ->where('id', $id)
            ->where('status', 'Draft')->first();
        if (isset($data->created_by)) {
            $user = $this->userSsoQueryServices->getUserByGuid($data->created_by);
        }
        $data->created_by_name = $user == null ? 'Tidak ada' : $user[0]['nama'];
        return $data;
    }

    public function findByIdDetail(string $id)
    {
        return PemutihanAsset::where('id', $id)->where('status', 'Publish')->first();
    }
}
