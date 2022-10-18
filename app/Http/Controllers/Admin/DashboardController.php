<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;

class DashboardController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetServiceQueryServices;

    public function __construct(
        AssetDataQueryServices $assetDataQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices
    )
    {
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
}
