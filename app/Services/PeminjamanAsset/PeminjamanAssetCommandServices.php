<?php

namespace App\Services\PeminjamanAsset;

use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Models\AssetData;
use Exception;
use App\Models\PeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Models\Approval;

class PeminjamanAssetCommandServices
{
    public function store(PeminjamanAssetStoreRequest $request)
    {
        $request->validated();
        $user = \Session::get('user');

        $peminjaman = new PeminjamanAsset();
        $peminjaman->guid_peminjam_asset = $user->guid;
        $peminjaman->json_peminjam_asset = json_encode($user);
        $peminjaman->tanggal_peminjaman = $request->tanggal_peminjaman;
        $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
        $peminjaman->alasan_peminjaman = $request->alasan_peminjaman;
        $peminjaman->status = 'pending';
        $peminjaman->created_by = $user->guid;
        $peminjaman->save();

        // ! kemungkinan ada update untuk store detail asset menggunakan multi store
        $asset_data = AssetData::findOrFail($request->id_asset);
        $detail_peminjaman = new DetailPeminjamanAsset();
        $detail_peminjaman->id_peminjaman_asset = $peminjaman->id;
        $detail_peminjaman->id_asset = $request->id_asset;
        $detail_peminjaman->json_asset_data = json_encode($asset_data);
        $detail_peminjaman->save();

        // ! guid approver ganti dengan guid dari manager
        $approval = new Approval();
        $approval->guid_approver = '4fda8944-535f-103c-9a64-bfc54612c8d8';
        $approval->approvable_type = get_class($peminjaman);
        $approval->approvable_id = $peminjaman->id;
        $approval->save();

        return $peminjaman;
    }
}
