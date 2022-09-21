<?php

namespace App\Services\AssetService;

use Carbon\Carbon;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Services\UserSso\UserSsoQueryServices;

class AssetServiceDatatableServices
{
    protected $ssoServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }
    public function datatable(Request $request)
    {
        $query = Service::query();
        $query->select(
            'services.id',
            'kategori_services.nama_service',
            'services.tanggal_mulai',
            'services.deskripsi_service',
            'services.status_service',
            'services.guid_pembuat'
        );
        $query->join('kategori_services', 'kategori_services.id', '=', 'services.id_kategori_service');
        $query->orderBy('services.created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('tanggal_mulai', function ($item) {
                return Carbon::parse($item->tanggal_mulai)->format('d M Y');
            })
            ->editColumn('kategori_service', function ($item) {
                return $item->nama_service;
            })
            ->editColumn('user', function ($item) {
                $user = $item->guid_pembuat == null ? null : $this->userSsoQueryServices->getUserByGuid($item->guid_pembuat);
                return isset($user[0]) ? $user[0]['name'] : 'Not Found';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAsset(this)" data-url_detail="' . route('admin.listing-asset.service-asset.show', $item->id) . '" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
