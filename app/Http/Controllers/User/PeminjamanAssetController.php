<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Services\PeminjamanAsset\PeminjamanAssetCommandServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\KategoriAsset\KategoriAssetQueryServices;
use Illuminate\Support\Facades\DB;

class PeminjamanAssetController extends Controller
{
    protected $peminjamanAssetCommandServices;
    protected $peminjamanAssetQueryServices;
    protected $assetDataQueryServices;
    protected $kategoriAssetQueryServices;

    public function __construct(
        PeminjamanAssetCommandServices $peminjamanAssetCommandServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices,
        AssetDataQueryServices $assetDataQueryServices,
        KategoriAssetQueryServices $kategoriAssetQueryServices
    ) {
        $this->peminjamanAssetCommandServices = $peminjamanAssetCommandServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->kategoriAssetQueryServices = $kategoriAssetQueryServices;
    }

    public function index()
    {
        return view('pages.user.asset.peminjaman.index');
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

    public function getAllData(Request $request)
    {
        try {
            $peminjaman = $this->peminjamanAssetQueryServices->findAll($request)->map(function ($item) {
                $item->link_detail = route('user.asset-data.peminjaman.detail', $item->id);
                $item->tanggal_peminjaman = date('d/m/Y', strtotime($item->tanggal_peminjaman));
                $item->tanggal_pengembalian = date('d/m/Y', strtotime($item->tanggal_pengembalian));
                $item->asset_data = json_decode($item->detail_peminjaman_asset->json_asset_data);
                $item->asset_data->kategori_asset = $this->kategoriAssetQueryServices->findById($item->asset_data->id_kategori_asset);
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data peminjaman asset berhasil didapatkan',
                'data' => $peminjaman
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Data peminjaman asset gagal didapatkan',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
