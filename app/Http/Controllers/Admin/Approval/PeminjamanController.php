<?php

namespace App\Http\Controllers\Admin\Approval;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\PeminjamanApprovalUpdate;
use App\Services\Approval\ApprovalQueryServices;
use App\Services\PeminjamanAsset\PeminjamanAssetCommandServices;

class PeminjamanController extends Controller
{
    protected $peminjamanAssetCommandServices;
    protected $approvalQueryServices;
    public function __construct(
        PeminjamanAssetCommandServices $peminjamanAssetCommandServices,
        ApprovalQueryServices $approvalQueryServices
    ) {
        $this->peminjamanAssetCommandServices = $peminjamanAssetCommandServices;
        $this->approvalQueryServices = $approvalQueryServices;
    }

    public function index()
    {
        $list_approval = $this->approvalQueryServices->findAll("App\\Models\\PeminjamanAsset");
        $total_approval = $list_approval->count();
        return view('pages.admin.approval.peminjaman.index', compact('total_approval'));
    }

    public function changeStatusApproval(PeminjamanApprovalUpdate $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->peminjamanAssetCommandServices->changeApprovalStatus($request, $id);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status approval',
                'data' => $data,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
