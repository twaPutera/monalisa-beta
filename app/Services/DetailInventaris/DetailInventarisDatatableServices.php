<?php

namespace App\Services\DetailInventaris;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\DetailInventoriData;

class DetailInventarisDatatableServices
{
    public function datatable(Request $request)
    {
        $query = DetailInventoriData::query();
        $query->with(['inventori_data', 'lokasi']);
        $query->where('id_inventori', $request->id_inventaris);
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kode_inventori', function ($item) {
                return $item->inventori_data->kode_inventori;
            })
            ->addColumn('nama_inventori', function ($item) {
                return $item->inventori_data->nama_inventori;
            })
            ->addColumn('lokasi', function ($item) {
                return $item->lokasi->nama_lokasi;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form action="' . route('admin.detail-inventaris.delete', [$item->id]) . '" class="form-confirm" method="POST">';
                $element .= csrf_field();
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.detail-inventaris.edit', [$item->id]) . '" data-url_update="' . route('admin.detail-inventaris.update', [$item->id]) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
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
