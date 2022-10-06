<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetChangeStatusRequest;
use Illuminate\Support\Facades\DB;

class PemindahanAssetController extends Controller
{
    protected $pemindahanAssetQueryServices;
    protected $pemindahanAssetCommandServices;
    protected $assetDataQueryServices;
    protected $assetServiceQueryServices;

    public function __construct(
        PemindahanAssetQueryServices $pemindahanAssetQueryServices,
        PemindahanAssetCommandServices $pemindahanAssetCommandServices,
        AssetDataQueryServices $assetDataQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices
    )
    {
        $this->pemindahanAssetQueryServices = $pemindahanAssetQueryServices;
        $this->pemindahanAssetCommandServices = $pemindahanAssetCommandServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
    }

    public function detail($id)
    {
        $user = \Session::get('user');
        $pemindahan_asset = $this->pemindahanAssetQueryServices->findById($id);
        $approval = $pemindahan_asset->approval->where('guid_approver', $user->guid)->first();
        $asset_data = $this->assetDataQueryServices->findById($pemindahan_asset->detail_pemindahan_asset->id_asset);
        $last_service = $this->assetServiceQueryServices->findLastestLogByAssetId($pemindahan_asset->detail_pemindahan_asset->id_asset);
        return view('pages.user.asset.pemindahan-asset.detail', compact('pemindahan_asset', 'asset_data', 'last_service', 'approval'));
    }

    public function approve(PemindahanAssetChangeStatusRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $pemindahan_asset = $this->pemindahanAssetCommandServices->changeStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status',
                'data' => $pemindahan_asset
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
