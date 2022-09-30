<?php

namespace App\Services\PemindahanAsset;

use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\PemindahanAsset;
use App\Services\UserSso\UserSsoQueryServices;

class PemindahanDatatableServices
{
    protected $userSsoQueryServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function datatable(Request $request)
    {
        $query = PemindahanAsset::query()
            ->with(['detail_pemindahan_asset', 'approval_pemindahan_asset']);

        if (isset($request->status)) {
            $query->where('status', $request->status);
        }

        if (isset($request->id_asset)) {
            $query->whereHas('detail_pemindahan_asset', function ($q) use ($request) {
                $q->where('id_asset', $request->id_asset);
            });
        }

        if (isset($request->guid_approver)) {
            $query->whereHas('approval_pemindahan_asset', function ($q) use ($request) {
                $q->where('guid_approver', $request->guid_approver);
            });
        }

        $query->orderBy('created_at', 'desc');
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('btn_download', function ($item) {
                $element = '<a href="'. route('admin.listing-asset.pemindahan-asset.print-bast', $item->id) .'" target="_blank" class="btn btn-icon"><i class="fa fa-download"></i></a>';
                return $element;
            })
            ->addColumn('penyerah', function ($item) {
                $penyerah = json_decode($item->json_penyerah_asset);
                return $penyerah->nama ?? 'Not Found';
            })
            ->addColumn('penerima', function ($item) {
                $penerima = json_decode($item->json_penerima_asset);
                return $penerima->nama ?? 'Not Found';
            })
            ->addColumn('asset', function ($item) {
                $asset = json_decode($item->detail_pemindahan_asset->json_asset_data, true);
                return $asset;
            })
            ->addColumn('pembuat', function ($item) {
                $user = $item->created_by == null ? null : $this->userSsoQueryServices->getUserByGuid($item->created_by);
                return isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            })
            ->rawColumns(['btn_download', 'asset'])
            ->make(true);
    }
}
