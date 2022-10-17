<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetService\AssetServiceCommandServices;
use App\Http\Requests\UserAssetService\UserAssetServiceStoreRequest;

class AssetServicesController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetServiceCommandServices;

    public function __construct(
        AssetServiceCommandServices $assetServiceCommandServices,
        AssetDataQueryServices $assetDataQueryServices
    ) {
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetServiceCommandServices = $assetServiceCommandServices;
    }

    public function create($id)
    {
        $asset_data = $this->assetDataQueryServices->findById($id);
        if ($asset_data->is_pemutihan != 1) {
            return view('pages.user.asset.services.create', compact('asset_data'));
        }
        abort(404);
    }

    public function store(UserAssetServiceStoreRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $asset_service = $this->assetServiceCommandServices->storeUserServices($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan service asset',
                'data' => $asset_service,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
