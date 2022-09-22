<?php

namespace App\Services\AssetService;

use App\Models\Service;
use App\Models\AssetData;
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
                if ($item->status_service == 'onprogress') {
                    $status = 'on progress';
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
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="editService(this)" data-url_edit="' . route('admin.services.edit', $item->id) . '" data-url_update="' . route('admin.services.update', $item->id) . '" class="btn btn-sm btn-primary btn-icon"><i class="fa fa-edit"></i></button>';
                return $element;
            })
            ->addColumn('asset_data', function ($item) {
                $asset = AssetData::query()
                    ->join('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset')
                    ->join('group_kategori_assets', 'group_kategori_assets.id', '=', 'kategori_assets.id_group_kategori_asset')
                    ->select([
                        'asset_data.id',
                        'asset_data.deskripsi',
                        'kategori_assets.nama_kategori',
                        'group_kategori_assets.nama_group',
                    ])->where('asset_data.id', $item->detail_service->id_asset_data)
                    ->first();

                return $asset->toArray();
            })
            ->addColumn('checkbox', function ($item) {
                $element = '';
                $element .= '<input type="checkbox" name="id[]" value="' . $item->id . '">';
                return $element;
            })
            ->rawColumns(['btn_show_service', 'asset_data', 'action', 'checkbox'])
            ->make(true);
    }
}
