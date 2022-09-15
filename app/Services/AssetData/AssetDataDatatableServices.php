<?php

namespace App\Services\AssetData;

use Yajra\DataTables\DataTables;
use App\Models\AssetData;
use App\Models\AssetImage;
use Illuminate\Http\Request;

class AssetDataDatatableServices
{
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
                return 'User';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAsset(this)" data-url_detail="'. route('admin.listing-asset.show', $item->id) .'" class="btn btn-sm btn-icon">
                                <i class="fa fa-eye"></i>
                            </button>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
