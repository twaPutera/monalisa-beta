<?php

namespace App\Http\Controllers\Admin\History;

use App\Exports\SummaryAssetExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SummaryAssetController extends Controller
{
    public function index()
    {
        return view('pages.admin.report.asset.index');
    }

    public function download(Request $request)
    {
        return Excel::download(new SummaryAssetExport($request->id_lokasi, $request->id_kategori_asset), 'laporan-summary-asset.xlsx');
    }
}
