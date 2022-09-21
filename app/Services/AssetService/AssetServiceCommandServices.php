<?php

namespace App\Services\AssetService;

use App\Models\Service;
use App\Models\AssetImage;
use App\Helpers\FileHelpers;
use App\Models\DetailService;
use App\Http\Requests\AssetService\AssetServiceStoreRequest;

class AssetServiceCommandServices
{
    public function store(string $id, AssetServiceStoreRequest $request)
    {
        $request->validated();
        $user = \Session::get('user');

        $asset_service = new Service();
        $asset_service->id_kategori_service = $request->id_kategori_service;
        $asset_service->guid_pembuat = $user->guid;
        $asset_service->deskripsi_service = $request->deskripsi_service;
        $asset_service->tanggal_mulai = $request->tanggal_mulai_service;
        $asset_service->status_service = $request->status_service == 'onprogress' ? 'On Progress' : 'Backlog';
        $asset_service->save();

        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $id;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->kondisi_asset_sebelum = $request->kondisi_sebelum;
        $detail_asset_service->biaya_service = 0;
        $detail_asset_service->save();

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_service->id);
            $path = storage_path('app/images/asset-service');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_service);
            $asset_images->imageable_id = $asset_service->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $asset_service;
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-service-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
}
