<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Approval\ApprovalQueryServices;
use App\Services\Pengaduan\PengaduanQueryServices;
use App\Services\Notification\NotificationQueryServices;
use App\Services\Notification\NotificationCommandServices;
use App\Services\PeminjamanAsset\PeminjamanAssetQueryServices;
use App\Services\SistemConfig\SistemConfigQueryServices;

class DashboardController extends Controller
{
    protected $pengaduanQueryServices;
    protected $peminjamanAssetQueryServices;
    protected $notificationQueryServices;
    protected $notificationCommandServices;
    protected $sistemConfigQueryServices;
    protected $approvalQueryServices;

    public function __construct(
        PengaduanQueryServices $pengaduanQueryServices,
        PeminjamanAssetQueryServices $peminjamanAssetQueryServices,
        NotificationQueryServices $notificationQueryServices,
        NotificationCommandServices $notificationCommandServices,
        SistemConfigQueryServices $sistemConfigQueryServices,
        ApprovalQueryServices $approvalQueryServices
    ) {
        $this->pengaduanQueryServices = $pengaduanQueryServices;
        $this->peminjamanAssetQueryServices = $peminjamanAssetQueryServices;
        $this->notificationQueryServices = $notificationQueryServices;
        $this->notificationCommandServices = $notificationCommandServices;
        $this->sistemConfigQueryServices = $sistemConfigQueryServices;
        $this->approvalQueryServices = $approvalQueryServices;
    }
    public function index()
    {
        return view('pages.user.dashboard');
    }

    public function about()
    {
        $tentang_aplikasi = $this->sistemConfigQueryServices->findByConfig('tentang_aplikasi');
        return view('pages.user.about', compact('tentang_aplikasi'));
    }

    public function getDashboardData(Request $request)
    {
        $data_pengaduan = $this->pengaduanQueryServices->countDataByCreatedById($request->created_by);
        $data_peminjaman = $this->peminjamanAssetQueryServices->countDataByGuidPeminjamAsset($request->created_by);
        $data_approval = $this->approvalQueryServices->countByGuidApprover($request->created_by);
        return response()->json([
            'success' => true,
            'data' => [
                'total_aduan' => $data_pengaduan,
                'total_peminjaman' => $data_peminjaman,
                'total_approval' => $data_approval,
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

    public function countNotification(Request $request)
    {
        try {
            $count = $this->notificationQueryServices->countNotificationUser($request->user_id);
            return response()->json([
                'success' => true,
                'data' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
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
