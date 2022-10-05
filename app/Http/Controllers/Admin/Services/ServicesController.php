<?php

namespace App\Http\Controllers\Admin\Services;

use Illuminate\Http\Request;
use App\Models\DetailService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Lokasi\LokasiQueryServices;
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
    protected $lokasiQueryServices;

    public function __construct(
        AssetServiceCommandServices $assetServiceCommandServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        AssetServiceDatatableServices $assetServiceDatatableServices,
        LokasiQueryServices $lokasiQueryServices
    ) {
        $this->assetServiceCommandServices = $assetServiceCommandServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->assetServiceDatatableServices = $assetServiceDatatableServices;
        $this->lokasiQueryServices = $lokasiQueryServices;
    }

    public function index()
    {
        $allServices = $this->assetServiceQueryServices->findAll();
        $allLokasi = $this->lokasiQueryServices->findAll();
        $allDetail = DetailService::all();
        $data['totalService'] = $allServices->count();
        $data['onProgress'] = $allServices->where('status_service', 'on progress')->count();
        $data['selesai'] = $allServices->where('status_service', 'selesai')->count();
        $data['backlog'] = $allServices->where('status_service', 'backlog')->count();
        $data['totalLokasi'] = 0;
        $data['namaLokasi'] = 'Tidak Ada';
        foreach ($allLokasi as $itemLokasi) {
            foreach ($allDetail as $itemServices) {
                if ($itemServices->where('id_lokasi', $itemLokasi->id)->count() >= $data['totalLokasi']) {
                    $data['namaLokasi'] =  $itemServices->where('id_lokasi', $itemLokasi->id)->first()->lokasi->nama_lokasi;
                    $data['totalLokasi'] = $itemServices->where('id_lokasi', $itemLokasi->id)->count();
                }
            }
        }
        return view('pages.admin.services.index', compact('data'));
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
