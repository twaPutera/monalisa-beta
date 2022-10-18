<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AssetData\AssetDataQueryServices;

class DashboardController extends Controller
{
    protected $assetDataQueryServices;

    public function __construct(AssetDataQueryServices $assetDataQueryServices)
    {
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function index()
    {
        return view('pages.admin.dashboard.index');
    }

    public function getSummaryDashboard(Request $request)
    {
        try {
            $countAsset = $this->assetDataQueryServices->countAsset($request);

            return response()->json([
                'success' => true,
                'data' => [
                    'countAsset' => number_format($countAsset, 0, ',', '.'),
                ]
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
