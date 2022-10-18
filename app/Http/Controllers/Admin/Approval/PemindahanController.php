<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PemindahanAsset\PemindahanAssetCommandServices;
use App\Services\PemindahanAsset\PemindahanAssetQueryServices;
use App\Http\Requests\PemindahanAsset\PemindahanAssetChangeStatusRequest;
use App\Services\Approval\ApprovalQueryServices;
use Illuminate\Support\Facades\DB;

class PemindahanController extends Controller
{
    protected $pemindahanAssetCommandServices;
    protected $pemindahanAssetQueryServices;
    protected $approvalQueryServices;
    public function __construct(
        PemindahanAssetCommandServices $pemindahanAssetCommandServices,
        PemindahanAssetQueryServices $pemindahanAssetQueryServices,
        ApprovalQueryServices $approvalQueryServices
    ) {
        $this->pemindahanAssetCommandServices = $pemindahanAssetCommandServices;
        $this->pemindahanAssetQueryServices = $pemindahanAssetQueryServices;
        $this->approvalQueryServices = $approvalQueryServices;
    }

    public function index()
    {
        $list_approval = $this->approvalQueryServices->findAll("App\\Models\\PemindahanAsset");
        $total_approval = $list_approval->where('is_approve', null)->orWhere('is_approve', 0)->count();
        return view('pages.admin.approval.pemindahan.index', compact('total_approval'));
    }

    public function changeStatusApproval(PemindahanAssetChangeStatusRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->pemindahanAssetCommandServices->changeStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status pemindahan asset',
                'data' => $data,
            ], 200);
            //code...
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
