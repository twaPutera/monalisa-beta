<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Pengaduan\PengaduanQueryServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use App\Services\Notification\NotificationQueryServices;
use App\Services\Notification\NotificationCommandServices;

class DashboardController extends Controller
{
    protected $pengaduanQueryServices;
    protected $peminjamanAssetQueryServices;
    protected $notificationQueryServices;
    protected $notificationCommandServices;

    public function __construct(
        PengaduanQueryServices $pengaduanQueryServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices,
        NotificationQueryServices $notificationQueryServices,
        NotificationCommandServices $notificationCommandServices
    ) {
        $this->pengaduanQueryServices = $pengaduanQueryServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
        $this->notificationQueryServices = $notificationQueryServices;
        $this->notificationCommandServices = $notificationCommandServices;
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

    public function notification()
    {
        return view('pages.user.notification');
    }

    public function getNotificationData(Request $request)
    {
        try {
            $data = $this->notificationQueryServices->findNotificationUser($request);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function readNotification(Request $request)
    {
        try {
            $data = $this->notificationCommandServices->readNotification($request->id);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
