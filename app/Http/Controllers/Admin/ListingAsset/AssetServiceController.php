<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Services\AssetService\AssetServiceCommandServices;
use App\Http\Requests\AssetService\AssetServiceStoreRequest;
use App\Services\AssetService\AssetServiceDatatableServices;
use Illuminate\Http\Request;

class AssetServiceController extends Controller
{
    protected $assetServiceCommandServices;
    protected $assetServiceDatatableServices;
    protected $assetServiceQueryServices;
    protected $userSsoQueryServices;

    public function __construct(
        AssetServiceCommandServices $assetServiceCommandServices,
        AssetServiceDatatableServices $assetServiceDatatableServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        UserSsoQueryServices $userSsoQueryServices
    ) {
        $this->assetServiceCommandServices = $assetServiceCommandServices;
        $this->assetServiceDatatableServices = $assetServiceDatatableServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->userSsoQueryServices = $userSsoQueryServices;
    }

    public function store(AssetServiceStoreRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $asset_service = $this->assetServiceCommandServices->store($id, $request);
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

    public function datatable(Request $request)
    {
        return $this->assetServiceDatatableServices->datatable($request);
    }
}
