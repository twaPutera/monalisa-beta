<?php

namespace App\Services\PeminjamanAsset;

use Exception;
use App\Models\Approval;
use App\Models\PeminjamanAsset;
use App\Models\RequestPeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Services\UserSso\UserSsoQueryServices;
use App\Http\Requests\Approval\PeminjamanApprovalUpdate;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\DetailPeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetChangeStatusRequest;
use App\Models\AssetData;

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

        if (! isset($approver[0])) {
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

        foreach ($request->id_jenis_asset as $id_jenis_asset) {
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

    public function changeApprovalStatus(PeminjamanApprovalUpdate $request, $id)
    {
        $request->validated();

        $peminjaman = PeminjamanAsset::findOrFail($id);
        $peminjaman->status = $request->status == 'disetujui' ? 'diproses' : 'ditolak';
        $peminjaman->save();

        $approval = $peminjaman->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = \Session::get('user')->guid;
        $approval->is_approve = $request->status == 'disetujui' ? 1 : 0;
        $approval->keterangan = $request->keterangan;
        $approval->save();

        return $peminjaman;
    }

    public function storeManyDetailPeminjaman(DetailPeminjamanAssetStoreRequest $request)
    {
        $request->validated();

        $peminjaman = PeminjamanAsset::findOrFail($request->id_peminjaman_asset);

        foreach ($request->id_asset as $id_asset) {
            $asset_data = AssetData::find($id_asset);
            $detail_peminjaman = new DetailPeminjamanAsset();
            $detail_peminjaman->id_peminjaman_asset = $peminjaman->id;
            $detail_peminjaman->json_asset_data = json_encode($asset_data);
            $detail_peminjaman->id_asset = $id_asset;
            $detail_peminjaman->save();
        }

        $request = self::getRequestQuota($peminjaman->id);

        return $request;
    }

    public function deleteDetailPeminjaman(string $id)
    {
        $detail_peminjaman = DetailPeminjamanAsset::findOrFail($id);
        $detail_peminjaman->delete();

        $request = self::getRequestQuota($detail_peminjaman->id_peminjaman_asset);

        return $request;
    }

    public function changeStatus(PeminjamanAssetChangeStatusRequest $request, $id)
    {
        $peminjaman = PeminjamanAsset::findOrFail($id);
        $peminjaman->status = $request->status;
        $peminjaman->save();

        return $peminjaman;
    }

    private static function getRequestQuota(string $id)
    {
        $request = RequestPeminjamanAsset::where('id_peminjaman_asset', $id)
                    ->join('kategori_assets', 'kategori_assets.id', '=', 'request_peminjaman_assets.id_kategori_asset')
                    ->select(
                        'id_kategori_asset as id',
                        'nama_kategori',
                        'jumlah'
                    )
                    ->get();
        return $request;
    }
}
