<?php

namespace App\Services\AssetService;

use App\Http\Requests\AssetService\AssetServiceStoreRequest;
use App\Models\DetailService;
use App\Models\Service;

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
        $asset_service->status_service = "On Progress";
        $asset_service->save();

        $detail_asset_service = new DetailService();
        $detail_asset_service->id_asset_data = $id;
        $detail_asset_service->id_service = $asset_service->id;
        $detail_asset_service->kondisi_asset_sebelum = $request->kondisi_sebelum;
        $detail_asset_service->biaya_service = 0;
        $detail_asset_service->save();

        return $asset_service;
    }
}
