<?php

namespace App\Services\Keluhan;

use Carbon\Carbon;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use App\Models\LogPengaduanAsset;
use App\Services\User\UserQueryServices;
use Yajra\DataTables\Facades\DataTables;
use App\Services\UserSso\UserSsoQueryServices;

class KeluhanDatatableServices
{
    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function datatableLog(Request $request)
    {
        $query = LogPengaduanAsset::query();
        $query->where('id_pengaduan', $request->id_pengaduan);
        $query->orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addColumn('tanggal', function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d') ?? 'Tidak Ada';
            })
            ->addColumn('message_log', function ($item) {
                return $item->message_log ?? 'Tidak Ada';
            })
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
            ->make(true);
    }

    public function datatable(Request $request)
    {
        $query = Pengaduan::query();
        $query->with(['asset_data', 'asset_data.lokasi', 'image', 'lokasi']);
        if (isset($request->id_lokasi)) {
            $query->orWhere('id_lokasi', $request->id_lokasi);
        }
        if (isset($request->id_kategori_asset)) {
            $query->whereHas('asset_data', function ($query) use ($request) {
                $query->where('id_kategori_asset', $request->id_kategori_asset);
            });
        }

        if (isset($request->awal)) {
            $query->where('tanggal_pengaduan', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $query->where('tanggal_pengaduan', '<=', $request->akhir);
        }

        if (isset($request->keyword)) {
            $query->where('catatan_pengaduan', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->status_pengaduan)) {
            if ($request->status_pengaduan != 'all') {
                $query->where('status_pengaduan', $request->status_pengaduan);
            }
        }

        if (isset($request->prioritas_pengaduan)) {
            if ($request->prioritas_pengaduan != 'all') {
                $query->where('prioritas', $request->prioritas_pengaduan);
            }
        }

        if (isset($request->limit)) {
            $query->limit($request->limit);
        }

        $filter = $request->toArray();
        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';

        if ($order_column_index == 2) {
            $query->orderBy('tanggal_pengaduan', $order_column_dir);
        }
        // $query->orderBy('tanggal_pengaduan', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_keluhan', function ($item) {
                return ! empty($item->tanggal_pengaduan) ? $item->tanggal_pengaduan : '-';
            })
            ->addColumn('kode_pengaduan', function ($item) {
                return ! empty($item->kode_pengaduan) ? $item->kode_pengaduan : '-';
            })
            ->addColumn('nama_asset', function ($item) {
                return ! empty($item->asset_data->deskripsi) ? $item->asset_data->deskripsi : '-';
            })
            ->addColumn('lokasi_asset', function ($item) {
                return ! empty($item->lokasi->nama_lokasi) ? $item->lokasi->nama_lokasi : '-';
            })
            ->addColumn('prioritas_pengaduan', function ($item) {
                return ! empty($item->prioritas) ? $item->prioritas : '-';
            })
            ->addColumn('catatan_pengaduan', function ($item) {
                return ! empty($item->catatan_pengaduan) ? $item->catatan_pengaduan : '-';
            })
            ->addColumn('created_by_name', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                    $name = isset($user[0]) ? collect($user[0]) : null;
                } else {
                    $user = $this->userQueryServices->findById($item->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('gambar_pengaduan', function ($item) {
                $data = '';
                $data .= '<button type="button" onclick="showKeluhanImage(this)"';
                $data .= 'data-url_detail="' . route('admin.keluhan.get-image', $item->id) . '"';
                $data .= 'class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $data;
            })
            ->addColumn('status_pengaduan', function ($item) {
                return ! empty($item->status_pengaduan) ? $item->status_pengaduan : '-';
            })
            ->addColumn('catatan_admin', function ($item) {
                return ! empty($item->catatan_admin) ? $item->catatan_admin : '-';
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status_pengaduan != 'selesai') {
                    $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.keluhan.edit', $item->id) . '" data-url_update="' . route('admin.keluhan.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                    <i class="fa fa-edit"></i>
                                </button>';
                    $element .= '<button type="button" onclick="detail(this)" data-url_detail="' . route('admin.keluhan.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-info">
                                    <i class="fa fa-eye"></i>
                                </button>';
                } else {
                    $element .= '<button type="button" onclick="detail(this)" data-url_detail="' . route('admin.keluhan.detail', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-info">
                                    <i class="fa fa-eye"></i>
                                </button>';
                }
                return $element;
            })
            ->rawColumns(['action', 'gambar_pengaduan'])
            ->make(true);
    }

    public function datatableHistoryPengaduan(Request $request)
    {
        $query = LogPengaduanAsset::query();
        $filter = $request->toArray();
        $query->join('pengaduans', 'log_pengaduan_assets.id_pengaduan', '=', 'pengaduans.id');
        $query->leftJoin('lokasis', 'pengaduans.id_lokasi', '=', 'lokasis.id');
        $query->leftJoin('asset_data', 'pengaduans.id_asset_data', '=', 'asset_data.id');
        $query->leftJoin('kategori_assets', 'asset_data.id_kategori_asset', '=', 'kategori_assets.id');
        $query->leftJoin('group_kategori_assets', 'kategori_assets.id_group_kategori_asset', '=', 'group_kategori_assets.id');
        $query->select([
            'pengaduans.kode_pengaduan',
            'pengaduans.id as id_pengaduan',
            'pengaduans.tanggal_pengaduan',
            'pengaduans.prioritas',
            'asset_data.deskripsi',
            'group_kategori_assets.nama_group',
            'kategori_assets.nama_kategori',
            'lokasis.nama_lokasi',
            'pengaduans.created_by',
            'pengaduans.catatan_pengaduan',
            'pengaduans.catatan_admin',
            'log_pengaduan_assets.status',
            'log_pengaduan_assets.created_at as log_terakhir',
            'log_pengaduan_assets.message_log',
            'log_pengaduan_assets.created_by as dilakukan_oleh',
        ]);

        if (isset($request->id_lokasi)) {
            $query->where('pengaduans.id_lokasi', $request->id_lokasi);
        }
        if (isset($request->id_kategori_asset)) {
            $query->where('asset_data.id_kategori_asset', $request->id_kategori_asset);
        }

        if (isset($request->awal)) {
            $query->where('pengaduans.tanggal_pengaduan', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $query->where('pengaduans.tanggal_pengaduan', '<=', $request->akhir);
        }

        if (isset($request->keyword)) {
            $query->where('pengaduans.catatan_pengaduan', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->id_asset_data)) {
            $query->where('pengaduans.id_asset_data', $request->id_asset_data);
        }

        if (isset($request->status_pengaduan)) {
            if ($request->status_pengaduan != 'all') {
                $query->where('log_pengaduan_assets.status', $request->status_pengaduan);
            }
        }

        // SORT

        $order_column_index = $filter['order'][0]['column'] ?? 0;
        $order_column_dir = $filter['order'][0]['dir'] ?? 'desc';
        if (1 == $order_column_index) {
            $query->orderBy('pengaduans.tanggal_pengaduan', $order_column_dir);
        }
        if (2 == $order_column_index) {
            $query->orderBy('log_pengaduan_assets.created_at', $order_column_dir);
        }
        if (3 == $order_column_index) {
            $query->orderBy('asset_data.deskripsi', $order_column_dir);
        }
        if (4 == $order_column_index) {
            $query->orderBy('lokasis.nama_lokasi', $order_column_dir);
        }
        if (5 == $order_column_index) {
            $query->orderBy('pengaduans.catatan_pengaduan', $order_column_dir);
        }
        if (6 == $order_column_index) {
            $query->orderBy('log_pengaduan_assets.status', $order_column_dir);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_keluhan', function ($item) {
                return ! empty($item->tanggal_pengaduan) ? $item->tanggal_pengaduan : '-';
            })
            ->addColumn('kode_pengaduan', function ($item) {
                return ! empty($item->kode_pengaduan) ? $item->kode_pengaduan : '-';
            })
            ->addColumn('nama_asset', function ($item) {
                return ! empty($item->deskripsi) ? $item->deskripsi : '-';
            })
            ->addColumn('lokasi_asset', function ($item) {
                return ! empty($item->nama_lokasi) ? $item->nama_lokasi : '-';
            })
            ->addColumn('catatan_pengaduan', function ($item) {
                return ! empty($item->catatan_pengaduan) ? $item->catatan_pengaduan : '-';
            })
            ->addColumn('prioritas_pengaduan', function ($item) {
                return ! empty($item->prioritas) ? $item->prioritas : '-';
            })
            ->addColumn('created_by_name', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                    $name = isset($user[0]) ? collect($user[0]) : null;
                } else {
                    $user = $this->userQueryServices->findById($item->created_by);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->addColumn('gambar_pengaduan', function ($item) {
                $data = '';
                $data .= '<button type="button" onclick="showKeluhanImage(this)"';
                $data .= 'data-url_detail="' . route('admin.keluhan.get-image', $item->id_pengaduan) . '"';
                $data .= 'class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $data;
            })
            ->addColumn('status_pengaduan', function ($item) {
                return ! empty($item->status) ? $item->status : '-';
            })
            ->addColumn('message_log', function ($item) {
                return ! empty($item->message_log) ? $item->message_log : '-';
            })
            ->addColumn('log_terakhir', function ($item) {
                return ! empty($item->log_terakhir) ? $item->log_terakhir : '-';
            })
            ->addColumn('dilakukan_oleh', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->dilakukan_oleh == null ? null : $this->userSsoQueryServices->getUserByGuid($item->dilakukan_oleh);
                    $name = isset($user[0]) ? collect($user[0]) : null;
                } else {
                    $user = $this->userQueryServices->findById($item->dilakukan_oleh);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })
            ->rawColumns(['gambar_pengaduan'])
            ->make(true);
    }
}
