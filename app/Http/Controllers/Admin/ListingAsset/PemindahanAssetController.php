<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\PemindahanAsset\PemindahanDatatableServices;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetStoreRequest;

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

    public function datatable(Request $request)
    {
        return $this->pemindahanDatatableServices->datatable($request);
    }

    public function printBast($id)
    {
        $data = $this->pemindahanAssetQueryServices->findById($id);
        $pdf = \PDF::loadView('pages.admin.pemindahan-asset.bast.index', compact('data'));
        return $pdf->stream();
        // return view('pages.admin.pemindahan-asset.bast.index', compact('data'));
    }
}
