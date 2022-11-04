<?php

namespace App\Http\Controllers\Admin\History;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use App\Services\AssetService\AssetServiceDatatableServices;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryServiceController extends Controller
{
    protected $assetServiceDatatableServices;
    public function __construct(AssetServiceDatatableServices $assetServiceDatatableServices)
    {
        $this->assetServiceDatatableServices = $assetServiceDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.service.index');
    }

    public function download(Request $request)
    {
        return Excel::download(new ServiceExport($request->tgl_awal, $request->tgl_akhir, $request->id_lokasi, $request->id_kategori_asset, $request->status_service, $request->id_asset_data), 'laporan-history-services.xlsx');
    }

    public function datatable(Request $request)
    {
        return $this->assetServiceDatatableServices->datatableHistoryServices($request);
    }
}
