<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AssetService\AssetServiceCommandServices;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Services\AssetService\AssetServiceDatatableServices;
use Illuminate\Support\Facades\DB;

class ServicesController extends Controller
{
    protected $assetServiceCommandServices;
    protected $assetServiceQueryServices;
    protected $assetServiceDatatableServices;

    public function __construct(
        AssetServiceCommandServices $assetServiceCommandServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        AssetServiceDatatableServices $assetServiceDatatableServices
    ) {
        $this->assetServiceCommandServices = $assetServiceCommandServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->assetServiceDatatableServices = $assetServiceDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.services.index');
    }

    public function datatable(Request $request)
    {
        return $this->assetServiceDatatableServices->datatable($request);
    }
}
