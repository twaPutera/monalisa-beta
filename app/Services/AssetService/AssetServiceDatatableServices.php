<?php

namespace App\Services\AssetService;

use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Services\UserSso\UserSsoQueryServices;

class AssetServiceDatatableServices
{
    public function datatable(Request $request)
    {
        $query = Service::query();
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
