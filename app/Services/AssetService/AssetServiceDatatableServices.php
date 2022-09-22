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
        $query = Service::query()
            ->with(['detail_service', 'kategori_service', 'image']);

        if (isset($request->id_asset_data)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->where('id_asset_data', $request->id_asset_data);
            });
        }

        if (isset($request->id_kategori_service)) {
            $query->where('id_kategori_service', $request->id_kategori_service);
        }

        $query->orderBy('services.created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_service', function ($item) {
                return $item->kategori_service->nama_service ?? 'no data';
            })
            ->addColumn('user', function ($item) {
                $user = $item->guid_pembuat == null ? null : $this->userSsoQueryServices->getUserByGuid($item->guid_pembuat);
                return isset($user[0]) ? $user[0]['name'] : 'Not Found';
            })
            ->addColumn('status_service', function ($item) {
                if ($item->status_service == "onprogress") {
                    $status = "on progress";
                } else {
                    $status = $item->status_service;
                }
                return $status;
            })
            ->addColumn('deskripsi_service', function ($item) {
                return $item->detail_service->catatan;
            })
            ->addColumn('btn_show_service', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAssetServices(this)" data-url_detail="' . route('admin.listing-asset.service-asset.show', $item->id) . '" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $element;
            })
            ->rawColumns(['btn_show_service'])
            ->make(true);
    }
}
