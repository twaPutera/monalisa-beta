<?php

namespace App\Services\AssetService;

use Carbon\Carbon;
use App\Models\Service;
use App\Models\AssetData;
use Illuminate\Http\Request;
use App\Models\LogServiceAsset;
use Yajra\DataTables\DataTables;
use App\Services\User\UserQueryServices;
use Yajra\DataTables\Contracts\DataTable;
use App\Services\UserSso\UserSsoQueryServices;

class AssetServiceDatatableServices
{
    protected $ssoServices;
    protected $userQueryServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
        $this->userQueryServices = new UserQueryServices();
    }

    public function datatableLog(Request $request)
    {
        $query = LogServiceAsset::query();
        $query->where('id_service', $request->id_service);
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
        $query = Service::query()
            ->with(['detail_service', 'kategori_service', 'image']);

        if (isset($request->id_asset_data)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->where('id_asset_data', $request->id_asset_data);
            });
        }

        if (isset($request->id_kategori_service)) {
            $query->where('id_kategori_service', $request->id_kategori_service);
        }

        if (isset($request->status_service)) {
            $query->where('status_service', $request->status_service);
        }

        if (isset($request->id_lokasi)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->where('id_lokasi', $request->id_lokasi);
            });
        }

        if (isset($request->awal)) {
            $query->where('tanggal_selesai', '>=', $request->awal);
        }

        if (isset($request->akhir)) {
            $query->where('tanggal_selesai', '<=', $request->akhir);
        }

        if (isset($request->year)) {
            $query->whereYear('tanggal_mulai', $request->year);
        }

        if (isset($request->month)) {
            $query->whereMonth('tanggal_mulai', $request->month);
        }

        if (isset($request->keyword)) {
            $query->whereHas('detail_service', function ($query) use ($request) {
                $query->where('permasalahan', 'like', '%' . $request->keyword . '%');
            });
        }

        $query->orderBy('services.created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_service', function ($item) {
                return $item->kategori_service->nama_service ?? 'Tidak Ada';
            })
            ->addColumn('user', function ($item) {
                $name = 'Not Found';
                if (config('app.sso_siska')) {
                    $user = $item->guid_pembuat == null ? null : $this->userSsoQueryServices->getUserByGuid($item->guid_pembuat);
                    $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
                } else {
                    $user = $this->userQueryServices->findById($item->guid_pembuat);
                    $name = isset($user) ? $user->name : 'Not Found';
                }
                return $name;
            })

            ->addColumn('deskripsi_service', function ($item) {
                return $item->detail_service->catatan ?? 'Tidak Ada';
            })
            ->addColumn('btn_show_service', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="showAssetServices(this)" data-url_detail="' . route('admin.listing-asset.service-asset.show', $item->id) . '" class="btn btn-sm btn-icon"><i class="fa fa-image"></i></button>';
                return $element;
            })
            ->addColumn('action', function ($item) {
                $element = '';
                if ($item->status_service != 'selesai') {
                    $element .= '<button type="button" onclick="editService(this)" data-url_edit="' . route('admin.services.edit', $item->id) . '" data-url_update="' . route('admin.services.update', $item->id) . '" class="btn btn-sm btn-warning mr-1 me-1 btn-icon"><i class="fa fa-edit"></i></button>';
                    $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                } else {
                    $element .= '<button type="button" onclick="detailService(this)" data-url_detail="' . route('admin.services.detail', $item->id) . '" class="btn btn-sm btn-primary mr-1 me-1 btn-icon"><i class="fa fa-eye"></i></button>';
                }
                return $element;
            })
            ->addColumn('asset_data', function ($item) {
                $asset = AssetData::query()
                    ->join('kategori_assets', 'kategori_assets.id', '=', 'asset_data.id_kategori_asset')
                    ->join('group_kategori_assets', 'group_kategori_assets.id', '=', 'kategori_assets.id_group_kategori_asset')
                    ->select([
                        'asset_data.id',
                        'asset_data.deskripsi',
                        'asset_data.is_inventaris',
                        'kategori_assets.nama_kategori',
                        'group_kategori_assets.nama_group',
                    ])->where('asset_data.id', $item->detail_service->id_asset_data)
                    ->first();
                return isset($asset) ? $asset->toArray() : [];
            })
            ->addColumn('checkbox', function ($item) {
                $element = '';
                $element .= '<input type="checkbox" name="id[]" value="' . $item->id . '">';
                return $element;
            })
            ->rawColumns(['btn_show_service', 'asset_data', 'action', 'checkbox'])
            ->make(true);
    }
}
