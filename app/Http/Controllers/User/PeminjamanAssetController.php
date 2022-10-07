<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Services\PeminjamanAsset\PeminjamanAssetCommandServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use Illuminate\Support\Facades\DB;

class PeminjamanAssetController extends Controller
{
    protected $peminjamanAssetCommandServices;
    protected $peminjamanAssetQueryServices;

    public function __construct(
        PeminjamanAssetCommandServices $peminjamanAssetCommandServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices
    ) {
        $this->peminjamanAssetCommandServices = $peminjamanAssetCommandServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
    }

    public function create()
    {
        return view('pages.user.asset.peminjaman.create');
    }

    public function store(PeminjamanAssetStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $peminjaman = $this->peminjamanAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Peminjaman asset berhasil dibuat',
                'data' => $peminjaman
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman asset gagal dibuat',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
