<?php

namespace App\Services\InventarisData;

use Illuminate\Http\Request;
use App\Models\InventoriData;
use Yajra\DataTables\DataTables;

class InventarisDataDatatableServices
{
    public function datatable(Request $request)
    {
        $query = InventoriData::query();
        $query->with(['kategori_inventori', 'satuan_inventori']);
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori', function ($item) {
                return $item->kategori_inventori->nama_kategori;
            })
            ->addColumn('satuan', function ($item) {
                return $item->stok . ' ' . $item->satuan_inventori->nama_satuan;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<form action="' . route('admin.listing-inventaris.delete', $item->id) . '" class="form-confirm" method="POST">';
                $element .= csrf_field();
                $element .= '<a href="' . route('admin.detail-inventaris.index', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary"><i class="fa fa-eye"></i></a>';
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.listing-inventaris.edit', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
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
