<?php

namespace App\Services\AssetData;

use App\Models\AssetData;
use App\Services\UserSso\UserSsoQueryServices;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AssetDataDatatableServices
{
    protected $ssoServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function datatable(Request $request)
    {
        $query = AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image']);
        // $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('group', function ($item) {
                return $item->kategori_asset->group_kategori_asset->nama_group;
            })
            ->addColumn('owner_name', function ($item) {
                $user = $this->userSsoQueryServices->getUserByGuid($item->ownership);
                return isset($user[0]) ? $user[0]['name'] : 'Not Found';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAsset(this)" data-url_detail="'. route('admin.listing-asset.show', $item->id) .'" class="btn btn-sm btn-icon">
                                <i class="fa fa-eye"></i>
                            </button>';
                return $element;
            })
            ->addColumn('register_oleh', function ($item) {
                $user = $this->userSsoQueryServices->getUserByGuid($item->register_oleh);
                return isset($user[0]) ? $user[0]['name'] : 'Not Found';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
