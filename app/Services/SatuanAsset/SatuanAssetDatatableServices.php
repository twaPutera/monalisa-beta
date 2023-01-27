<?php

namespace App\Services\SatuanAsset;

use App\Models\SatuanAsset;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SatuanAssetDatatableServices
{
    public function datatable(Request $request)
    {
        $query = SatuanAsset::query();
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form action="' . route('admin.setting.satuan-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                $element .= csrf_field();
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.setting.satuan-asset.edit', $item->id) . '" data-url_update="' . route('admin.setting.satuan-asset.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>';
                $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                <i class="fa fa-trash"></i>
                            </button>';
                $element .= '</form>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
