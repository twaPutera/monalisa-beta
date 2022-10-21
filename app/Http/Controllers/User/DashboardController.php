<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Pengaduan\PengaduanQueryServices;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $pengaduanQueryServices;
    public function __construct(
        PengaduanQueryServices $pengaduanQueryServices
    ) {
        $this->pengaduanQueryServices = $pengaduanQueryServices;
    }
    public function index()
    {
        return view('pages.user.dashboard');
    }

    public function getDashboardData(Request $request)
    {
        $data_pengaduan = $this->pengaduanQueryServices->findAll($request)->count();
        return response()->json([
            'success' => true,
            'data' => [
                'total_aduan' => $data_pengaduan,
            ]
        ]);
    }
}
