<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Pengaduan\PengaduanQueryServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;

class DashboardController extends Controller
{
    protected $pengaduanQueryServices;
    protected $peminjamanAssetQueryServices;
    public function __construct(
        PengaduanQueryServices $pengaduanQueryServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices
    ) {
        $this->pengaduanQueryServices = $pengaduanQueryServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
    }
    public function index()
    {
        return view('pages.user.dashboard');
    }

    public function getDashboardData(Request $request)
    {
        $data_pengaduan = $this->pengaduanQueryServices->countDataByCreatedById($request->created_by);
        $data_peminjaman = $this->peminjamanAssetQueryServices->countDataByGuidPeminjamAsset($request->created_by);
        return response()->json([
            'success' => true,
            'data' => [
                'total_aduan' => $data_pengaduan,
                'total_peminjaman' => $data_peminjaman
            ],
        ]);
    }

    public function profile()
    {
        return view('pages.user.profile');
    }
}
