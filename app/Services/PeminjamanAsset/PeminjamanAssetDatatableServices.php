<?php

namespace App\Services\PeminjamanAsset;

use Illuminate\Http\Request;
use App\Models\PeminjamanAsset;
use Yajra\DataTables\DataTables;
use App\Models\DetailPeminjamanAsset;

class PeminjamanAssetDatatableServices
{
    public function datatable(Request $request)
    {
        $query = PeminjamanAsset::query()
            ->with(['approval']);

        if (isset($request->status)) {
            $query->where('status', $request->status);
        }

        if (isset($request->guid_peminjam_asset)) {
            $query->where('guid_peminjam_asset', $request->guid_peminjam_asset);
        }

        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_peminjam', function ($row) {
                $peminjam = json_decode($row->json_peminjam_asset);
                return $peminjam->name;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<a href="'. route('admin.peminjaman.detail', $item->id) .'" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                <i class="fa fa-eye"></i>
                            </a>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detailPeminjamanDatatable(Request $request)
    {
        $query = DetailPeminjamanAsset::query();

        if (isset($request->id_peminjaman_asset)) {
            $query->where('id_peminjaman_asset', $request->id_peminjaman_asset);
        }

        if (isset($request->id_asset)) {
            $query->where('id_asset', $request->id_asset);
        }

        $query->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('asset_data', function ($row) {
                $asset_data = json_decode($row->json_asset_data, true);
                return $asset_data;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->peminjaman_asset->status == 'diproses') {
                    $element .= '<form action="' . route('admin.peminjaman.detail-asset.delete', $item->id) . '" class="form-confirm" method="POST">';
                    $element .= csrf_field();
                    $element .= '<button type="submit" class="btn btn-sm btn-icon btn-danger btn-confirm">
                                    <i class="fa fa-trash"></i>
                                </button>';
                    $element .= '</form>';
                }
                return $element;
            })
            ->rawColumns(['action', 'asset_data'])
            ->make(true);
    }
}
