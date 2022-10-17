<?php

namespace App\Services\InventarisData;

use Illuminate\Http\Request;
use App\Models\InventoriData;
use Yajra\DataTables\DataTables;
use App\Models\LogPenambahanInventori;
use App\Models\LogPenguranganInventori;

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
                return ! empty($item->kategori_inventori->nama_kategori) ? $item->kategori_inventori->nama_kategori : 'Tidak Ada';
            })
            ->addColumn('sebelumnya', function ($item) {
                return ! empty($item->jumlah_sebelumnya) || ! empty($item->satuan_inventori->nama_satuan) ? $item->jumlah_sebelumnya . ' ' . $item->satuan_inventori->nama_satuan : 'Tidak Ada';
            })
            ->addColumn('saat_ini', function ($item) {
                return ! empty($item->jumlah_saat_ini) || ! empty($item->satuan_inventori->nama_satuan) ? $item->jumlah_saat_ini . ' ' . $item->satuan_inventori->nama_satuan : 'Tidak Ada';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="detail(this)" data-url_detail="' . route('admin.listing-inventaris.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                <i class="fa fa-eye"></i>
                            </button>';
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.listing-inventaris.edit', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>';
                $element .= '<button type="button" onclick="stokEdit(this)" data-url_edit="' . route('admin.listing-inventaris.edit.stok', $item->id) . '" data-url_update="' . route('admin.listing-inventaris.update.stok', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-info">
                                <i class="fa fa-box"></i>
                            </button>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function datatablePenambahan(Request $request)
    {
        $query = LogPenambahanInventori::query();
        $query->with(['inventori_data']);
        $query->where('id_inventori', $request->id_inventaris);
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($item) {
                return $item->jumlah . ' ' . $item->inventori_data->satuan_inventori->nama_satuan;
            })
            ->make(true);
    }

    public function datatablePengurangan(Request $request)
    {
        $query = LogPenguranganInventori::query();
        $query->with(['inventori_data']);
        $query->where('id_inventori', $request->id_inventaris);
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('jumlah', function ($item) {
                return $item->jumlah . ' ' . $item->inventori_data->satuan_inventori->nama_satuan;
            })
            ->make(true);
    }
}
