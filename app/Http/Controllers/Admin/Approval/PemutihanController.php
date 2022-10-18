<?php

namespace App\Http\Controllers\Admin\Approval;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approval\PemutihanApprovalUpdate;
use App\Services\Approval\ApprovalQueryServices;
use App\Services\PemutihanAsset\PemutihanAssetCommandServices;

class PemutihanController extends Controller
{
    protected $pemutihanAssetCommandServices;
    protected $approvalQueryServices;

    public function __construct(
        PemutihanAssetCommandServices $pemutihanAssetCommandServices,
        ApprovalQueryServices $approvalQueryServices
    ) {
        $this->pemutihanAssetCommandServices = $pemutihanAssetCommandServices;
        $this->approvalQueryServices = $approvalQueryServices;
    }

    public function index()
    {
        $list_approval = $this->approvalQueryServices->findAll("App\\Models\\PemutihanAsset");
        $total_approval = $list_approval->where('is_approve', null)->orWhere('is_approve', 0)->count();
        return view('pages.admin.approval.pemutihan.index', compact('total_approval'));
    }

    public function changeStatusApproval(PemutihanApprovalUpdate $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = $this->pemutihanAssetCommandServices->changeApprovalStatus($request, $id);
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
