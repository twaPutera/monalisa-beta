<?php

namespace App\Http\Controllers\Admin\PeminjamanAsset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PeminjamanAsset\PeminjamanAssetCommandServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use App\Services\PeminjamanAsset\PeminjamanAssetDatatableServices;
use Illuminate\Support\Facades\DB;

class PeminjamanAssetController extends Controller
{
    protected $peminjamanAssetCommandServices;
    protected $peminjamanAssetQueryServices;
    protected $peminjamanAssetDatatableServices;

    public function __construct(
        PeminjamanAssetCommandServices $peminjamanAssetCommandServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices,
        PeminjamanAssetDatatableServices $peminjamanAssetDatatableServices
    ) {
        $this->peminjamanAssetCommandServices = $peminjamanAssetCommandServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
        $this->peminjamanAssetDatatableServices = $peminjamanAssetDatatableServices;
    }

    public function show($id)
    {
        try {
            $data = $this->peminjamanAssetQueryServices->findById($id);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
