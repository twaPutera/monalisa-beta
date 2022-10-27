<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DepresiasiAsset\DepresiasiAssetDatatableServices;
use App\Exports\DepresiasiExport;

class DepresiasiController extends Controller
{
    protected $depresiasiAssetDatatableServices;

    public function __construct(DepresiasiAssetDatatableServices $depresiasiAssetDatatableServices)
    {
        $this->depresiasiAssetDatatableServices = $depresiasiAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.depresiasi.index');
    }

    public function datatable(Request $request)
    {
        return $this->depresiasiAssetDatatableServices->datatable($request);
    }

    public function download()
    {
        return (new DepresiasiExport(2018))->download('depresiasi.xlsx');
    }
}
