<?php

namespace App\Services\PemindahanAsset;

use App\Models\PemindahanAsset;
use App\Models\PemindahanAssetDetail;
use App\Models\AssetData;
use App\Models\ApprovalPemindahanAsset;
use App\Http\Requests\PemindahanAsset\PemindahanAssetStoreRequest;
use App\Models\DetailPemindahanAsset;
use App\Services\UserSso\UserSsoQueryServices;

class PemindahanAssetCommandServices
{
    protected $userSsoQueryServices;

    public function __construct(
        UserSsoQueryServices $userSsoQueryServices
    ) {
        $this->userSsoQueryServices = $userSsoQueryServices;
    }

    public function store(PemindahanAssetStoreRequest $request)
    {
        $request->validated();
        $user = \Session::get('user');
        $data_penerima = $this->userSsoQueryServices->getUserByGuid($request->penerima_asset);
        $data_penyerah = $this->userSsoQueryServices->getUserByGuid($request->penyerah_asset);
        $asset = AssetData::find($request->asset_id);

        $pemindahan_asset = new PemindahanAsset();
        $pemindahan_asset->no_surat = $request->no_bast;
        $pemindahan_asset->tanggal_pemindahan = $request->tanggal_pemindahan;
        $pemindahan_asset->guid_penerima_asset = $request->penerima_asset;
        $pemindahan_asset->guid_penyerah_asset = $request->penyerah_asset;
        $pemindahan_asset->json_penerima_asset = json_encode($data_penerima[0] ?? []);
        $pemindahan_asset->json_penyerah_asset = json_encode($data_penyerah[0] ?? []);
        $pemindahan_asset->status = 'pending';
        $pemindahan_asset->created_by = $user->guid;
        $pemindahan_asset->save();

        $pemindahan_asset_detail = new DetailPemindahanAsset();
        $pemindahan_asset_detail->id_pemindahan_asset = $pemindahan_asset->id;
        $pemindahan_asset_detail->id_asset = $asset->id;
        $pemindahan_asset_detail->json_asset_data = json_encode($asset);
        $pemindahan_asset_detail->save();

        $approval_pemindahan_asset_penerima = new ApprovalPemindahanAsset();
        $approval_pemindahan_asset_penerima->id_pemindahan_asset = $pemindahan_asset->id;
        $approval_pemindahan_asset_penerima->guid_approver = $request->penerima_asset;
        $approval_pemindahan_asset_penerima->save();

        return $pemindahan_asset;
    }
}
