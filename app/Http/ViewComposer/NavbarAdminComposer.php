<?php

namespace App\Http\ViewComposer;

use App\Services\Approval\ApprovalQueryServices;
use App\Services\UserSso\UserSsoQueryServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class NavbarAdminComposer
{
    protected $request;
    protected $approvalQueryServices;

    /**
     * ActiveMenuComposer constructor.
     *
     * @param Request $request
     */
    public function __construct(
        Request $request,
        ApprovalQueryServices $approvalQueryServices
    ) {
        $this->approvalQueryServices = $approvalQueryServices;
        $this->request = $request;
        $this->userSsoQueryServices = new UserSsoQueryServices();
    }

    public function compose(View $view)
    {
        $list_pemindahan_asset = $this->approvalQueryServices->findAll("App\\Models\\PemindahanAsset")->where('is_approve', null)->orWhere('is_approve', 0)->count();
        $list_pemutihan_asset = $this->approvalQueryServices->findAll("App\\Models\\PemutihanAsset")->where('is_approve', null)->orWhere('is_approve', 0)->count();
        $list_peminjaman_asset = $this->approvalQueryServices->findAll("App\\Models\\PeminjamanAsset")->where('is_approve', null)->orWhere('is_approve', 0)->count();
        $daftar_approval = $list_pemindahan_asset + $list_peminjaman_asset + $list_pemutihan_asset;
        $user = $this->userSsoQueryServices->getUserByGuid(Session::get('user')->guid);
        $jabatan = $user[0]['jabatanPegawai'] != null ? str_replace('_', ' ', $user[0]['jabatanPegawai']['nama']) : "Tidak Ada";
        $nama = $user[0]['nama'];
        $view->with([
            'daftar_approval' => $daftar_approval,
            'jabatan' => $jabatan,
            'nama' => $nama
        ]);
    }
}
