<?php

namespace App\Services\AssetData;

use App\Models\LogAsset;
use App\Models\AssetData;
use Illuminate\Http\Request;
use App\Models\LogAssetOpname;
use Yajra\DataTables\DataTables;
use App\Services\UserSso\UserSsoQueryServices;

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

        if (isset($request->searchKeyword)) {
            $query->where(function ($querySearch) use ($request) {
                $querySearch->where('deskripsi', 'like', '%' . $request->searchKeyword . '%')
                    ->orWhere('kode_asset', 'like', '%' . $request->searchKeyword . '%')
                    ->orWhere('no_seri', 'like', '%' . $request->searchKeyword . '%');
            });
        }

        if ($request->has('list_peminjaman')) {
            $query->whereDoesntHave('detail_peminjaman_asset', function ($query) use($request) {
                $query->whereHas('peminjaman_asset', function ($query) use($request) {
                    $query->where(function ($query) use($request) {
                        $query->where('status', 'dipinjam');
                        if (isset($request->id_peminjaman)) {
                            $query->orWhere('id', '=', $request->id_peminjaman);
                        }
                    });
                });
            });
        }

        if (isset($request->id_satuan_asset)) {
            $query->where('id_satuan_asset', $request->id_satuan_asset);
        }

        if (isset($request->id_vendor)) {
            $query->where('id_vendor', $request->id_vendor);
        }

        if (isset($request->id_lokasi) && $request->id_lokasi != 'root') {
            $query->where('id_lokasi', $request->id_lokasi);
        }

        if (isset($request->id_kelas_asset)) {
            $query->where('id_kelas_asset', $request->id_kelas_asset);
        }

        if (isset($request->id_kategori_asset)) {
            $query->where('id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->categories)) {
            $query->whereIn('id_kategori_asset', $request->categories);
        }

        if (isset($request->is_sparepart)) {
            $query->where('is_sparepart', $request->is_sparepart);
        }

        if (isset($request->is_pemutihan)) {
            $query->where('is_pemutihan', $request->is_pemutihan);
        }

        if (isset($request->status_kondisi)) {
            $query->where('status_kondisi', $request->status_kondisi);
        }

        if (isset($request->is_pinjam)) {
            $query->where('is_pinjam', $request->is_pinjam);
        }

        $query->where('is_pemutihan', 0);
        // $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('group', function ($item) {
                return $item->kategori_asset->group_kategori_asset->nama_group ?? 'Tidak ada Grup';
            })
            ->addColumn('nama_lokasi', function ($item) {
                return $item->lokasi->nama_lokasi ?? 'Tidak ada Lokasi';
            })
            ->addColumn('nama_vendor', function ($item) {
                return $item->vendor->nama_vendor ?? 'Tidak ada Vendor';
            })
            ->addColumn('nama_satuan', function ($item) {
                return $item->satuan_asset->nama_satuan ?? 'Tidak ada Satuan';
            })
            ->addColumn('nama_kategori', function ($item) {
                return $item->kategori_asset->nama_kategori ?? 'Tidak ada Kategori';
            })
            ->addColumn('owner_name', function ($item) {
                $user = $item->ownership == null ? null : $this->userSsoQueryServices->getUserByGuid($item->ownership);
                return isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAsset(this)" data-url_detail="' . route('admin.listing-asset.show', $item->id) . '" class="btn btn-sm btn-icon">
                                <i class="fa fa-eye"></i>
                            </button>';
                return $element;
            })
            ->addColumn('checkbox', function ($item) {
                $element = '';
                $element .= '<input type="checkbox" name="id_checkbox[]" value="' . $item->id . '">';
                return $element;
            })
            ->addColumn('register_oleh', function ($item) {
                $user = $this->userSsoQueryServices->getUserByGuid($item->register_oleh);
                return isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }

    public function log_asset_dt(Request $request)
    {
        $query = LogAsset::query();
        if (isset($request->asset_id)) {
            $query->where('asset_id', $request->asset_id);
        }
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->rawColumns([])
            ->make(true);
    }

    public function log_opname_dt(Request $request)
    {
        $query = LogAssetOpname::query();
        if (isset($request->asset_id)) {
            $query->where('id_asset_data', $request->asset_id);
        }
        $query->orderBy('created_at', 'ASC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('created_by', function ($item) {
                $user = $this->userSsoQueryServices->getUserByGuid($item->created_by);
                return isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showOpname(this)" data-url_detail="' . route('admin.listing-asset.log-opname.show', $item->id) . '" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
