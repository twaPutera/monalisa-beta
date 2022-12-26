<?php

namespace App\Http\Controllers\Admin\Inventaris;

use App\Http\Controllers\Controller;
use App\Services\InventarisData\InventarisDataDatatableServices;
use Illuminate\Http\Request;

class RequestBahanHabisPakaiController extends Controller
{
    protected $inventarisDataDatatableServices;
    public function __construct(InventarisDataDatatableServices $inventarisDataDatatableServices)
    {
        $this->inventarisDataDatatableServices = $inventarisDataDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.listing-inventaris.permintaan.index');
    }

    public function datatable(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatablePermintaan($request);
    }
}
