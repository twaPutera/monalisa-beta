<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AssetData\AssetDataQueryServices;

class AssetServicesController extends Controller
{
    protected $assetDataQueryServices;

    public function __construct(
        AssetDataQueryServices $assetDataQueryServices
    )
    {
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function create($id)
    {
        $asset_data = $this->assetDataQueryServices->findById($id);
        return view('pages.user.asset.services.create', compact('asset_data'));
    }
}
