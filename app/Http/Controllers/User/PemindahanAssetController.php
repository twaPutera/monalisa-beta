<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;

class PemindahanAssetController extends Controller
{
    protected $pemindahanAssetQueryServices;
    protected $assetDataQueryServices;
    protected $assetServiceQueryServices;

    public function __construct(
        PemindahanAssetQueryServices $pemindahanAssetQueryServices,
        AssetDataQueryServices $assetDataQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices
    ) {
        $this->pemindahanAssetQueryServices = $pemindahanAssetQueryServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
    }

    public function detail($id)
    {
        $pemindahan_asset = $this->pemindahanAssetQueryServices->findById($id);
        $asset_data = $this->assetDataQueryServices->findById($pemindahan_asset->detail_pemindahan_asset->id_asset);
        $last_service = $this->assetServiceQueryServices->findLastestLogByAssetId($pemindahan_asset->detail_pemindahan_asset->id_asset);
        return view('pages.user.asset.pemindahan-asset.detail', compact('pemindahan_asset', 'asset_data', 'last_service'));
    }
}
