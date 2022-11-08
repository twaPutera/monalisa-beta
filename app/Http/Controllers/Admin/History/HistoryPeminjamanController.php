<?php

namespace App\Http\Controllers\Admin\History;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PeminjamanAsset\PeminjamanAssetDatatableServices;

class HistoryPeminjamanController extends Controller
{
    protected $peminjamanAssetDatatableServices;

    public function __construct(PeminjamanAssetDatatableServices $peminjamanAssetDatatableServices)
    {
        $this->peminjamanAssetDatatableServices = $peminjamanAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.peminjaman.index');
    }

    public function datatable(Request $request)
    {
        return $this->peminjamanAssetDatatableServices->logPeminjamanDatatable($request);
    }
}
