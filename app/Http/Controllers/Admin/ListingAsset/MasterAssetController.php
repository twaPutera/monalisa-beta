<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetData\AssetDataCommandServices;
use App\Services\AssetData\AssetDataDatatableServices;

class MasterAssetController extends Controller
{
    protected $assetDataCommandServices;
    protected $assetDataDatatableServices;
    protected $assetDataQueryServices;

    public function __construct(
        AssetDataCommandServices $assetDataCommandServices,
        AssetDataDatatableServices $assetDataDatatableServices,
        AssetDataQueryServices $assetDataQueryServices
    ) {
        $this->assetDataCommandServices = $assetDataCommandServices;
        $this->assetDataDatatableServices = $assetDataDatatableServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function index()
    {
        return view('pages.admin.listing-asset.index');
    }

    public function datatable(Request $request)
    {
        return $this->assetDataDatatableServices->datatable($request);
    }

    public function store(AssetStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data asset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->assetDataQueryServices->findById($id);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data asset',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function detail($id)
    {
        $asset = $this->assetDataQueryServices->findById($id);
        return view('pages.admin.listing-asset.detail', compact('asset'));
    }

    public function previewImage(Request $request)
    {
        try {
            $path = storage_path('app/images/asset/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::viewFile($path, $filename);

            return $response;
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
