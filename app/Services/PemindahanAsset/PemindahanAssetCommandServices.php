<?php

namespace App\Services\PemindahanAsset;

use App\Http\Requests\PemindahanAsset\PemindahanAssetChangeStatusRequest;
use App\Models\PemindahanAsset;
use App\Models\PemindahanAssetDetail;
use App\Models\AssetData;
use App\Models\ApprovalPemindahanAsset;
use App\Http\Requests\PemindahanAsset\PemindahanAssetStoreRequest;
use App\Http\Requests\PemindahanAsset\PemindahanAssetUpdateRequest;
use App\Models\DetailPemindahanAsset;
use App\Services\UserSso\UserSsoQueryServices;
use App\Services\AssetData\AssetDataCommandServices;
use Exception;

class PemindahanAssetCommandServices
{
    protected $userSsoQueryServices;
    protected $assetDataCommandServices;

    public function __construct(
        UserSsoQueryServices $userSsoQueryServices,
        AssetDataCommandServices $assetDataCommandServices
    ) {
        $this->userSsoQueryServices = $userSsoQueryServices;
        $this->assetDataCommandServices = $assetDataCommandServices;
    }

    public function store(PemindahanAssetStoreRequest $request)
    {
        $request->validated();

        $check_pemindahan_asset = PemindahanAsset::query()->whereHas('detail_pemindahan_asset', function ($q) use ($request) {
            $q->where('id_asset', $request->asset_id);
        })->where('status', 'pending')->exists();

        if ($check_pemindahan_asset) {
            throw new Exception('Asset masih ada dalam pemindahan asset');
        }

        $user = \Session::get('user');
        $data_penerima = $this->userSsoQueryServices->getUserByGuid($request->penerima_asset);
        $data_penyerah = $this->userSsoQueryServices->getUserByGuid($request->penyerah_asset);
        // $data_jabatan_penyerah = $this->userSsoQueryServices->getDataPositionById($request->jabatan_penyerah);
        // $data_jabatan_penerima = $this->userSsoQueryServices->getDataPositionById($request->jabatan_penerima);
        // $data_unit_kerja_penyerah = $this->userSsoQueryServices->getUnitById($request->unit_kerja_penyerah);
        // $data_unit_kerja_penerima = $this->userSsoQueryServices->getUnitById($request->unit_kerja_penerima);
        $asset = AssetData::find($request->asset_id);

        $pemindahan_asset = new PemindahanAsset();
        $pemindahan_asset->no_surat = $request->no_bast;
        $pemindahan_asset->tanggal_pemindahan = $request->tanggal_pemindahan;
        $pemindahan_asset->guid_penerima_asset = $request->penerima_asset;
        $pemindahan_asset->guid_penyerah_asset = $request->penyerah_asset;
        $data_penerima_array = [
            'guid' => $data_penerima[0]['token_user'],
            'nama' => $data_penerima[0]['nama'],
            'email' => $data_penerima[0]['email'],
            'no_hp' => $data_penerima[0]['no_hp'],
            'no_induk' => $data_penerima[0]['no_induk'],
            'jabatan' => $request->jabatan_penerima,
            'unit_kerja' => $request->unit_kerja_penerima,
        ];
        $data_penyerah_array = [
            'guid' => $data_penyerah[0]['token_user'],
            'nama' => $data_penyerah[0]['nama'],
            'email' => $data_penyerah[0]['email'],
            'no_hp' => $data_penyerah[0]['no_hp'],
            'no_induk' => $data_penyerah[0]['no_induk'],
            'jabatan' => $request->jabatan_penyerah,
            'unit_kerja' => $request->unit_kerja_penyerah,
        ];
        $pemindahan_asset->json_penerima_asset = json_encode($data_penerima_array ?? []);
        $pemindahan_asset->json_penyerah_asset = json_encode($data_penyerah_array ?? []);
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

        $message_log = 'Pemindahan asset dengan nomor surat ' . $request->no_bast . ' berhasil dibuat pada asset ' . $asset->deskripsi;;
        $this->assetDataCommandServices->insertLogAsset($asset->id, $message_log);

        return $pemindahan_asset;
    }

    public function changeStatus(PemindahanAssetChangeStatusRequest $request)
    {
        $request->validated();
        $user = \Session::get('user');

        $approval_pemindahan_asset = ApprovalPemindahanAsset::query()
            ->where('id_pemindahan_asset', $request->id_pemindahan_asset)
            ->where('guid_approver', $user->guid)->where('status', 'pending')
            ->first();

        if (!$approval_pemindahan_asset) {
            throw new Exception('Anda tidak memiliki akses untuk mengubah status pemindahan asset');
        }

        $approval_pemindahan_asset->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval_pemindahan_asset->tanggal_approval = date('Y-m-d');
        $approval_pemindahan_asset->keterangan = $request->keterangan;
        $approval_pemindahan_asset->save();

        $pemindahan_asset = PemindahanAsset::find($approval_pemindahan_asset->id_pemindahan_asset);
        $pemindahan_asset->status = $request->status;
        $pemindahan_asset->save();

        $message_log = 'Pemindahan asset dengan nomor surat ' . $pemindahan_asset->no_surat . ' berhasil diubah statusnya menjadi ' . $request->status;
        $detail_pemindahan_asset = DetailPemindahanAsset::query()->where('id_pemindahan_asset', $pemindahan_asset->id)->first();
        $this->assetDataCommandServices->insertLogAsset($detail_pemindahan_asset->id_asset, $message_log);

        if ($request->status == 'disetujui') {
            $asset = AssetData::find($detail_pemindahan_asset->id_asset);
            $asset->ownership = $pemindahan_asset->guid_penerima_asset;
            $asset->save();
        }
    }
}
