<?php

namespace App\Services\DepresiasiAsset;

use App\Models\DepresiasiAsset;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DepresiasiAssetDatatableServices
{
    public function datatable(Request $request)
    {
        $query = DepresiasiAsset::query()
            ->with('asset_data');

        if (isset($request->id_asset_data)) {
            $query->where('id_asset_data', $request->id_asset_data);
        }

        if (isset($request->bulan_depresiasi)) {
            $query->whereMonth('tanggal_depresiasi', $request->bulan_depresiasi);
        }

        if (isset($request->tahun_depresiasi)) {
            $query->whereYear('tanggal_depresiasi', $request->tahun_depresiasi);
        }

        if (isset($request->group_kategori_asset)) {
            $query->whereHas('asset_data', function ($query) use ($request) {
                $query->whereHas('kategori_asset', function ($query) use ($request) {
                    $query->where('id_group_kategori_asset', $request->group_kategori_asset);
                });
            });
        }

        if (isset($request->kategori_asset)) {
            $query->whereHas('asset_data', function ($query) use ($request) {
                $query->where('id_kategori_asset', $request->kategori_asset);
            });
        }

        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('group', function ($item) {
                return $item->asset_data->kategori_asset->group_kategori_asset->nama_group ?? 'Tidak ada Grup';
            })
            ->addColumn('nama_lokasi', function ($item) {
                return $item->asset_data->lokasi->nama_lokasi ?? 'Tidak ada Lokasi';
            })
            ->addColumn('nama_vendor', function ($item) {
                return $item->asset_data->vendor->nama_vendor ?? 'Tidak ada Vendor';
            })
            ->addColumn('nama_satuan', function ($item) {
                return $item->asset_data->satuan_asset->nama_satuan ?? 'Tidak ada Satuan';
            })
            ->addColumn('nama_kategori', function ($item) {
                return $item->asset_data->kategori_asset->nama_kategori ?? 'Tidak ada Kategori';
            })
            ->make(true);
    }
}
