<?php

namespace App\Http\ViewComposer;

use App\Services\Approval\ApprovalQueryServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TabApprovalComposer
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
    }

    public function compose(View $view)
    {
        $list_pemindahan_asset = $this->approvalQueryServices->findAll("App\\Models\\PemindahanAsset");
        $list_pemutihan_asset = $this->approvalQueryServices->findAll("App\\Models\\PemutihanAsset");
        $list_peminjaman_asset = $this->approvalQueryServices->findAll("App\\Models\\PeminjamanAsset");
        $total_approval_pemindahan = $list_pemindahan_asset->where('is_approve', null)->orWhere('is_approve', 0)->count();
        $total_approval_pemutihan = $list_pemutihan_asset->where('is_approve', null)->orWhere('is_approve', 0)->count();
        $total_approval_peminjaman = $list_peminjaman_asset->where('is_approve', null)->orWhere('is_approve', 0)->count();
        $view->with([
            'total_approval_pemindahan' => $total_approval_pemindahan,
            'total_approval_pemutihan' => $total_approval_pemutihan,
            'total_approval_peminjaman' => $total_approval_peminjaman
        ]);
    }
}
