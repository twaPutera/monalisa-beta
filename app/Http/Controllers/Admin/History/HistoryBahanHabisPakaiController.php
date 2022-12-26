<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use App\Services\InventarisData\InventarisDataDatatableServices;
use Illuminate\Http\Request;

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
}
