<?php

namespace App\Services\PeminjamanAsset;

use Exception;
use App\Models\Approval;
use App\Models\AssetData;
use App\Models\PeminjamanAsset;
use App\Models\DetailPeminjamanAsset;
use App\Models\RequestPeminjamanAsset;
use App\Services\UserSso\UserSsoQueryServices;
use App\Http\Requests\Approval\PeminjamanApprovalUpdate;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\DetailPeminjamanAssetStoreRequest;
use App\Http\Requests\PeminjamanAsset\PeminjamanAssetChangeStatusRequest;
use Illuminate\Support\Facades\Session;
use App\Helpers\SsoHelpers;
use App\Http\Requests\PeminjamanAsset\PerpanjanganPeminjamanStoreRequest;
use App\Models\PerpanjanganPeminjamanAsset;

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
        $user = SsoHelpers::getUserLogin();
        // $approver = $this->userSsoQueryServices->getDataUserByRoleId($request, 34);

        // if (!isset($approver[0])) {
        //     throw new Exception('Tidak Manager Asset yang dapat melakukan approval!');
        // }

        $peminjaman = new PeminjamanAsset();
        $peminjaman->guid_peminjam_asset = config('app.sso_siska') ? $user->guid : $user->id;
        $peminjaman->json_peminjam_asset = json_encode($user);
        $peminjaman->tanggal_peminjaman = $request->tanggal_peminjaman;
        $peminjaman->tanggal_pengembalian = $request->tanggal_pengembalian;
        $peminjaman->alasan_peminjaman = $request->alasan_peminjaman;
        $peminjaman->status = 'pending';
        $peminjaman->created_by = config('app.sso_siska') ? $user->guid : $user->id;
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
        // $approval->guid_approver = $approver[0]['guid'];
        $approval->approvable_type = get_class($peminjaman);
        $approval->approvable_id = $peminjaman->id;
        $approval->save();

        return $peminjaman;
    }

    public function changeApprovalStatus(PeminjamanApprovalUpdate $request, $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();

        $peminjaman = PeminjamanAsset::findOrFail($id);
        $peminjaman->status = $request->status;
        $peminjaman->save();

        $approval = $peminjaman->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval->keterangan = $request->keterangan;
        $approval->save();

        return $peminjaman;
    }

    public function storeManyDetailPeminjaman(DetailPeminjamanAssetStoreRequest $request)
    {
        $request->validated();

        $peminjaman = PeminjamanAsset::findOrFail($request->id_peminjaman_asset);

        foreach ($request->id_asset as $id_asset) {
            $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $id_asset)->first();
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

    public function storeRequestPerpanjangan(PerpanjanganPeminjamanStoreRequest $request, $id_peminjaman)
    {
        $peminjaman = PeminjamanAsset::findOrFail($id_peminjaman);

        $user = SsoHelpers::getUserLogin();

        if ($peminjaman->tanggal_pengembalian > $request->tanggal_expired_perpanjangan) {
            throw new Exception('Tanggal perpanjangan tidak boleh kurang dari tanggal pengembalian');
        }

        $perpanjangan = new PerpanjanganPeminjamanAsset();
        $perpanjangan->id_peminjaman_asset = $peminjaman->id;
        $perpanjangan->tanggal_expired_sebelumnya = $request->tanggal_pengembalian;
        $perpanjangan->tanggal_expired_perpanjangan = $request->tanggal_expired_perpanjangan;
        $perpanjangan->alasan_perpanjangan = $request->alasan_perpanjangan;
        $perpanjangan->status = 'pending';
        $perpanjangan->save();

        $approval = new Approval();
        $approval->approvable_type = get_class($perpanjangan);
        $approval->approvable_id = $perpanjangan->id;
        $peminjaman->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->save();

        return $perpanjangan;
    }

    public function changeApprovalStatusPerpanjangan(PeminjamanApprovalUpdate $request, $id)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $perpanjangan = PerpanjanganPeminjamanAsset::findOrFail($id);
        $perpanjangan->status = $request->status;
        $perpanjangan->save();

        if ($request->status == 'disetujui') {
            $peminjaman = $perpanjangan->peminjaman_asset;
            $peminjaman->tanggal_pengembalian = $perpanjangan->tanggal_expired_perpanjangan;
            $peminjaman->save();
        }

        $approval = $perpanjangan->approval;
        $approval->tanggal_approval = date('Y-m-d H:i:s');
        $approval->guid_approver = config('app.sso_siska') ? $user->guid : $user->id;
        $approval->is_approve = $request->status == 'disetujui' ? '1' : '0';
        $approval->keterangan = $request->keterangan;
        $approval->save();

        return $perpanjangan;
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
