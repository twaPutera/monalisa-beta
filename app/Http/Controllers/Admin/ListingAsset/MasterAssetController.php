<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AssetData\AssetDataCommandServices;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Services\AssetData\AssetDataDatatableServices;
use App\Services\AssetData\AssetDataQueryServices;
use Illuminate\Support\Facades\DB;

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
}
