<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;
use App\Services\PemindahanAsset\PemindahanDatatableServices;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetStoreRequest;
use Illuminate\Support\Facades\DB;

class PemindahanAssetController extends Controller
{
    protected $pemindahanAssetCommandServices;
    protected $pemindahanDatatableServices;
    protected $pemindahanAssetQueryServices;

    public function __construct(
        PemindahanAssetCommandServices $pemindahanAssetCommandServices,
        PemindahanDatatableServices $pemindahanDatatableServices,
        PemindahanAssetQueryServices $pemindahanAssetQueryServices
    ) {
        $this->pemindahanAssetCommandServices = $pemindahanAssetCommandServices;
        $this->pemindahanDatatableServices = $pemindahanDatatableServices;
        $this->pemindahanAssetQueryServices = $pemindahanAssetQueryServices;
    }

    public function store(PemindahanAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $this->pemindahanAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan pemindahan asset',
                'data' => $data,
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
