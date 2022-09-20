<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Models\AssetImage;
use App\Services\UserSso\UserSsoQueryServices;
use App\Helpers\QrCodeHelpers;

class AssetDataQueryServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function findById(string $id)
    {
        $data =  AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image'])
            ->where('id', $id)
            ->firstOrFail();
        if (is_null($data->qr_code)) {
            $qr_name = 'qr-asset-' . $data->kode_asset . '.png';
            $path = storage_path('app/images/qr-code/' . $qr_name);
            $qr_code = QrCodeHelpers::generateQrCode($data->kode_asset, $path);
            $data->qr_code = $qr_name;
            $data->save();
        }
        $user = null;

        if (isset($data->ownership)) {
            $user = $this->userSsoQueryServices->getUserByGuid($data->ownership);
        }

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.image.preview') . '?filename=' . $item->path;
            return $item;
        });

        $data->link_detail = route('admin.listing-asset.detail', $data->id);
        $data->owner_name = $user == null ? 'Tidak ada' : $user[0]['name'];

        return $data;
    }

    public function findAssetImageById(string $id)
    {
        return AssetImage::query()
            ->where('id', $id)
            ->firstOrFail();
    }
}
