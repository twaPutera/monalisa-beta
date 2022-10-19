<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Approval\ApprovalQueryServices;
use Illuminate\Http\Request;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetServiceQueryServices;
    protected $approvalQueryServices;
    public function __construct(
        AssetDataQueryServices $assetDataQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        ApprovalQueryServices $approvalQueryServices
    ) {
        $this->approvalQueryServices = $approvalQueryServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
    }

    public function index()
    {
        return view('pages.admin.dashboard.index');
    }

    public function getSummaryDashboard(Request $request)
    {
        try {
            $countAsset = $this->assetDataQueryServices->countAsset($request);
            $lastUpdateAsset = $this->assetDataQueryServices->lastUpdateAsset();
            $nilai_asset = $this->assetDataQueryServices->getValueAsset();
            $data_summary_chart_asset = $this->assetDataQueryServices->getDataChartSummaryAssetByGroup($request);
            $data_summary_chart_asset_by_kondisi = $this->assetDataQueryServices->getDataChartSummaryAssetByStatus($request);
            $data_summary_chart_asset_by_month_regis = $this->assetDataQueryServices->getDataChartSummaryAssetByMonthRegister($request);
            $data_summary_service_by_status = $this->assetServiceQueryServices->getDataChartByStatus($request);

            return response()->json([
                'success' => true,
                'data' => [
                    'countAsset' => number_format($countAsset, 0, ',', '.'),
                    'lastUpdateAsset' => $lastUpdateAsset,
                    'nilaiAsset' => $nilai_asset,
                    'dataSummaryChartAsset' => $data_summary_chart_asset,
                    'dataSummaryChartAssetByKondisi' => $data_summary_chart_asset_by_kondisi,
                    'dataSummaryChartAssetByMonthRegis' => $data_summary_chart_asset_by_month_regis,
                    'dataSummaryServiceByStatus' => $data_summary_service_by_status
                ]
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getDaftarApproval(Request $request)
    {
        try {
            $list_pemindahan_asset = $this->approvalQueryServices->findAll("App\\Models\\PemindahanAsset");
            $list_pemutihan_asset = $this->approvalQueryServices->findAll("App\\Models\\PemutihanAsset");
            $list_peminjaman_asset = $this->approvalQueryServices->findAll("App\\Models\\PeminjamanAsset");

            $total_all_pemindahan = $list_pemindahan_asset->count();
            $total_all_peminjaman = $list_peminjaman_asset->count();
            $total_all_pemutihan = $list_pemutihan_asset->count();

            $total_pemindahan = $list_pemindahan_asset->where('is_approve', null)->orWhere('is_approve', 0)->count();
            $total_pemutihan = $list_pemutihan_asset->where('is_approve', null)->orWhere('is_approve', 0)->count();
            $total_peminjaman = $list_peminjaman_asset->where('is_approve', null)->orWhere('is_approve', 0)->count();

            if (Auth::user()->role == "manager") {
                $daftar_approval = $total_pemindahan + $total_peminjaman + $total_pemutihan;
            } else {
                $daftar_approval = $total_pemindahan + $total_peminjaman;
            }

            if ($request->url == 'peminjaman') {
                $approval_task = $total_all_peminjaman;
            } else if ($request->url == 'pemutihan') {
                $approval_task = $total_all_pemutihan;
            } else if ($request->url == 'pemindahan') {
                $approval_task = $total_all_pemindahan;
            } else {
                $approval_task = 0;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'total_approval_pemindahan' => $total_pemindahan,
                    'total_approval_pemutihan' => $total_pemutihan,
                    'total_approval_peminjaman' => $total_peminjaman,
                    'daftar_approval' => $daftar_approval,
                    'approval_task' => $approval_task
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
