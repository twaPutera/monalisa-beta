<?php

namespace App\Services\AssetData;

use App\Models\LogAsset;
use App\Models\AssetData;
use Illuminate\Http\Request;
use App\Models\LogAssetOpname;
use App\Models\Lokasi;
use Yajra\DataTables\DataTables;
use App\Services\User\UserQueryServices;
use App\Services\UserSso\UserSsoQueryServices;

class AssetDataDatatableServices
{
    protected $ssoServices;
    protected $userServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
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
            $query->whereDoesntHave('detail_peminjaman_asset', function ($query) use ($request) {
                $query->whereHas('peminjaman_asset', function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
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
            if ($request->is_pemutihan == 'all') {
                $query->where('is_pemutihan', 0);
                $query->orWhere('is_pemutihan', 1);
            } else {
                $query->where('is_pemutihan', $request->is_pemutihan);
            }
        }

        if (isset($request->status_kondisi)) {
            if ($request->status_kondisi != 'semua') {
                $query->where('status_kondisi', $request->status_kondisi);
            }
        }

        if (isset($request->jenis)) {
            $query->where('id_kategori_asset', $request->jenis);
        }

        if (isset($request->is_pinjam)) {
            $query->where('is_pinjam', $request->is_pinjam);
        }

        if (!isset($request->is_pemutihan)) {
            $query->where('is_pemutihan', 0);
        }
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
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $item->ownership == null ? null : $this->userSsoQueryServices->getUserByGuid($item->ownership);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $item->ownership == null ? null : $this->userQueryServices->findById($item->ownership);
                    $name = isset($user) ? $user->name : 'Not Found';
                }

                return $name;
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
                $element .= '<input type="checkbox" name="id_checkbox[]" onchange="checklistAsset(this)" class="check-item" value="' . $item->id . '">';
                return $element;
            })
            ->addColumn('register_oleh', function ($item) {
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $item->register_oleh == null ? null : $this->userSsoQueryServices->getUserByGuid($item->register_oleh);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $item->register_oleh == null ? null : $this->userQueryServices->findById($item->register_oleh);
                    $name = isset($user) ? $user->name : 'Not Found';
                }

                return $name;
            })
            ->rawColumns(['action', 'checkbox'])
            ->make(true);
    }
    public function datatableReport(Request $request)
    {
        $query = AssetData::query()
            ->with(['satuan_asset', 'vendor', 'lokasi', 'kelas_asset', 'kategori_asset', 'image', 'log_asset_opname', 'detail_peminjaman_asset', 'detail_pemindahan_asset']);

        if (isset($request->searchKeyword)) {
            $query->where(function ($querySearch) use ($request) {
                $querySearch->where('deskripsi', 'like', '%' . $request->searchKeyword . '%')
                    ->orWhere('kode_asset', 'like', '%' . $request->searchKeyword . '%')
                    ->orWhere('no_seri', 'like', '%' . $request->searchKeyword . '%');
            });
        }

        if (isset($request->id_lokasi) && $request->id_lokasi != 'root') {
            $query->where('id_lokasi', $request->id_lokasi);
        }

        if (isset($request->id_kategori_asset)) {
            $query->where('id_kategori_asset', $request->id_kategori_asset);
        }

        $query->where('is_pemutihan', 0);
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
            ->addColumn('tanggal_opname', function ($item) {
                $opname = $item->log_asset_opname->sortByDesc('created_at')->first();
                return $opname->tanggal_opname ?? '-';
            })
            ->addColumn('kode_opname', function ($item) {
                $opname = $item->log_asset_opname->sortByDesc('created_at')->first();
                return $opname->kode_opname ?? '-';
            })
            ->addColumn('catatan_opname', function ($item) {
                $opname = $item->log_asset_opname->sortByDesc('created_at')->first();
                return $opname->keterangan ?? '-';
            })
            ->addColumn('user_opname', function ($item) {
                $opname = $item->log_asset_opname->sortByDesc('created_at')->first();
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $opname == null ? null : $this->userSsoQueryServices->getUserByGuid($opname->created_by);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $opname == null ? null : $this->userQueryServices->findById($opname->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('tanggal_peminjaman', function ($item) {
                $peminjaman = $item->detail_peminjaman_asset->sortByDesc('created_at')->first();
                return $peminjaman->peminjaman_asset->tanggal_peminjaman ?? '-';
            })
            ->addColumn('tanggal_pengembalian', function ($item) {
                $peminjaman = $item->detail_peminjaman_asset->sortByDesc('created_at')->first();
                return $peminjaman->peminjaman_asset->tanggal_pengembalian ?? '-';
            })
            ->addColumn('status_peminjaman', function ($item) {
                $peminjaman = $item->detail_peminjaman_asset->sortByDesc('created_at')->first();
                return $peminjaman->peminjaman_asset->status ?? '-';
            })
            ->addColumn('user_peminjaman', function ($item) {
                $peminjaman = $item->detail_peminjaman_asset->sortByDesc('created_at')->first();
                $peminjam = $peminjaman ? json_decode($peminjaman->peminjaman_asset->json_peminjam_asset) : 'Not Found';
                $name = $peminjam->name ?? 'Not Found';
                return $name;
            })
            ->addColumn('tanggal_pemindahan', function ($item) {
                $pemindahan = $item->detail_pemindahan_asset->sortByDesc('created_at')->first();
                return $pemindahan->pemindahan_asset->tanggal_pemindahan ?? '-';
            })
            ->addColumn('user_penyerah', function ($item) {
                $pemindahan = $item->detail_pemindahan_asset->sortByDesc('created_at')->first();
                $penyerah = $pemindahan ? json_decode($pemindahan->pemindahan_asset->json_penyerah_asset) : 'Not Found';
                $name = $penyerah->nama ?? 'Not Found';
                return $name;
            })
            ->addColumn('user_penerima', function ($item) {
                $pemindahan = $item->detail_pemindahan_asset->sortByDesc('created_at')->first();
                $penerima = $pemindahan ? json_decode($pemindahan->pemindahan_asset->json_penerima_asset) : 'Not Found';
                $name = $penerima->nama ?? 'Not Found';
                return $name;
            })
            ->addColumn('owner_name', function ($item) {
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $item->ownership == null ? null : $this->userSsoQueryServices->getUserByGuid($item->ownership);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $item->ownership == null ? null : $this->userQueryServices->findById($item->ownership);
                    $name = isset($user) ? $user->name : 'Not Found';
                }

                return $name;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<a href ="' . route('admin.listing-asset.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></a>';
                return $element;
            })
            ->addColumn('register_oleh', function ($item) {
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $item->register_oleh == null ? null : $this->userSsoQueryServices->getUserByGuid($item->register_oleh);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $item->register_oleh == null ? null : $this->userQueryServices->findById($item->register_oleh);
                    $name = isset($user) ? $user->name : 'Not Found';
                }

                return $name;
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
        $filter = $request->toArray();
        $query = LogAssetOpname::query()
            ->select([
                'log_asset_opnames.*',
                'asset_data.deskripsi',
                'asset_data.kode_asset',
            ])
            ->join('asset_data', 'asset_data.id', '=', 'log_asset_opnames.id_asset_data');

        if (isset($request->asset_id)) {
            $query->where('id_asset_data', $request->asset_id);
        }

        if (isset($request->limit)) {
            $query->limit($request->limit);
        }

        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        if (3 == $order_column_index) {
            $query->orderBy('kritikal', $order_column_dir);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('created_by', function ($item) {
                $name = '-';
                if (config('app.sso_siska')) {
                    $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $item->created_by == null ? null : $this->userQueryServices->findById($item->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }

                return $name;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showOpname(this)" data-url_detail="' . route('admin.listing-asset.log-opname.show', $item->id) . '" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $element;
            })
            ->addColumn('lokasi_awal', function ($item) {
                $lokasi = Lokasi::where('id', $item->lokasi_sebelumnya)->first();
                return $lokasi->nama_lokasi ?? 'Tidak Ada';
            })
            ->addColumn('lokasi_akhir', function ($item) {
                return $item->lokasi->nama_lokasi ?? 'Tidak Ada';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
