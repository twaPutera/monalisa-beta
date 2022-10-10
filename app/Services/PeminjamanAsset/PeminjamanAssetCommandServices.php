<?php

namespace App\Services\PeminjamanAsset;

use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Models\AssetData;
use Exception;
use App\Models\PeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Models\RequestPeminjamanAsset;
use App\Models\Approval;
use App\Services\UserSso\UserSsoQueryServices;

class PeminjamanAssetCommandServices
{
    protected $userSsoQueryServices;

    public function __construct()
    {
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function store(PeminjamanAssetStoreRequest $request)
    {
        $request->validated();
        $user = \Session::get('user');
        $approver = $this->userSsoQueryServices->getDataUserByRoleId($request, 34);

        if (!isset($approver[0])) {
            throw new Exception('Tidak Manager Asset yang dapat melakukan approval!');
        }

        $peminjaman = new PeminjamanAsset();
        $peminjaman->guid_peminjam_asset = $user->guid;
        $peminjaman->json_peminjam_asset = json_encode($user);
        $peminjaman->tanggal_peminjaman = $request->tanggal_peminjaman;
        $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
        $peminjaman->alasan_peminjaman = $request->alasan_peminjaman;
        $peminjaman->status = 'pending';
        $peminjaman->created_by = $user->guid;
        $peminjaman->save();

        foreach($request->id_jenis_asset as $id_jenis_asset) {
            $request_kategori_detail = $request->data_jenis_asset[$id_jenis_asset];
            $request_peminjaman = new RequestPeminjamanAsset();
            $request_peminjaman->id_peminjaman_asset = $peminjaman->id;
            $request_peminjaman->id_kategori_asset = $id_jenis_asset;
            $request_peminjaman->jumlah = $request_kategori_detail['jumlah'];
            $request_peminjaman->save();
        }

        $approval = new Approval();
        $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($peminjaman);
        $approval->approvable_id = $peminjaman->id;
        $approval->save();

        return $peminjaman;
    }
}
