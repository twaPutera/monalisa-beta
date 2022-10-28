<?php

namespace App\Http\Controllers\Admin\History;

use App\Exports\PengaduanExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryPengaduanController extends Controller
{
    public function index()
    {
        return view('pages.admin.report.pengaduan.index');
    }

    public function download(Request $request)
    {
        return Excel::download(new PengaduanExport($request->tgl_awal, $request->tgl_akhir, $request->id_lokasi, $request->id_asset), 'laporan-history-pengaduan.xlsx');
    }
}
