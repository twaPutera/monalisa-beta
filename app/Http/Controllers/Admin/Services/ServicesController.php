<?php

namespace App\Http\Controllers\Admin\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Services\ServicesStoreRequest;
use App\Http\Requests\Services\ServicesUpdateRequest;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Services\AssetService\AssetServiceCommandServices;
use App\Services\AssetService\AssetServiceDatatableServices;

class ServicesController extends Controller
{
    protected $assetServiceCommandServices;
    protected $assetServiceQueryServices;
    protected $assetServiceDatatableServices;

    public function __construct(
        AssetServiceCommandServices $assetServiceCommandServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        AssetServiceDatatableServices $assetServiceDatatableServices
    ) {
        $this->assetServiceCommandServices = $assetServiceCommandServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->assetServiceDatatableServices = $assetServiceDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.services.index');
    }

    public function datatable(Request $request)
    {
        return $this->assetServiceDatatableServices->datatable($request);
    }

    public function store(ServicesStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $asset_service = $this->assetServiceCommandServices->storeServices($request);
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

    public function edit(string $id)
    {
        try {
            $service = $this->assetServiceQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data service',
                'data' => $service,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(ServicesUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $asset_service = $this->assetServiceCommandServices->updateServices($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah service asset',
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

    public function getDataChartServices(Request $request)
    {
        try {
            $data = $this->assetServiceQueryServices->getDataChartServices($request);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data chart service',
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
