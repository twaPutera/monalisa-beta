<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AssetData\AssetDataQueryServices;

class AssetController extends Controller
{
    protected $assetDataQueryServices;

    public function __construct(AssetDataQueryServices $assetDataQueryServices)
    {
        $this->assetDataQueryServices = $assetDataQueryServices;
    }

    public function getDataAssetByUser(Request $request)
    {
        try {
            $user = \Session::get('user', null);
            $data = $this->assetDataQueryServices->getDataAssetForDashboardUser($user->guid ?? '0')->map(function ($item) use ($user) {
                $item->link_detail = route('user.asset-data.detail', $item->id);
                $item->tanggal_diterima = date('d/m/Y', strtotime($item->tgl_register));
                $item->status_diterima = 'Diterima';
                $pemindahan_asset = $this->assetDataQueryServices->checkIsAssetOnPemindahanAsset($item->id, $user->guid);
                if (isset($pemindahan_asset)) {
                    if ($pemindahan_asset->pemindahan_asset->status == 'pending') {
                        $item->link_detail = route('user.asset-data.pemindahan.detail', $pemindahan_asset->id_pemindahan_asset);
                    }
                    $item->tanggal_diterima = $pemindahan_asset->pemindahan_asset->status != 'pending' ? date('d/m/Y', strtotime($pemindahan_asset->pemindahan_asset->tanggal_pemindahan)) : '-';
                    $item->status_diterima = $pemindahan_asset->pemindahan_asset->status != 'pending' ? 'Diterima' : 'Belum Diterima';
                }
                return $item;
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
