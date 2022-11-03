<?php

namespace App\Http\Controllers\Admin\History;

use App\Exports\ServiceExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryServiceController extends Controller
{
    public function index()
    {
        return view('pages.admin.report.service.index');
    }

    public function download(Request $request)
    {
        return Excel::download(new ServiceExport($request->tgl_awal, $request->tgl_akhir, $request->id_lokasi, $request->id_kategori_asset), 'laporan-history-services.xlsx');
    }
}
