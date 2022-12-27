<?php

namespace App\Http\Controllers\Admin\History;

use App\Exports\RequestBahanHabisPakaiExport;
use App\Http\Controllers\Controller;
use App\Services\InventarisData\InventarisDataDatatableServices;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryBahanHabisPakaiController extends Controller
{
    protected $inventarisDataDatatableServices;
    public function __construct(InventarisDataDatatableServices $inventarisDataDatatableServices)
    {
        $this->inventarisDataDatatableServices = $inventarisDataDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.report.bahan-habis-pakai.index');
    }

    public function datatable(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatableHistory($request);
    }

    public function download(Request $request)
    {
        return Excel::download(new RequestBahanHabisPakaiExport(
            $request->tgl_awal_permintaan,
            $request->tgl_akhir_permintaan,
            $request->tgl_awal_pengambilan,
            $request->tgl_akhir_pengambilan,
            $request->status_permintaan
        ), 'laporan-history-permintaan-bahan-habis-pakai.xlsx');
    }
}
