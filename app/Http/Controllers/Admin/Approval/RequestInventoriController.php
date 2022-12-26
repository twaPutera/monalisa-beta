<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InventarisData\InventarisDataCommandServices;
use App\Http\Requests\Approval\RequestInventoriUpdate;

class RequestInventoriController extends Controller
{
    protected $inventarisDataCommandServices;

    public function __construct(
        InventarisDataCommandServices $inventarisDataCommandServices
    ) {
        $this->inventarisDataCommandServices = $inventarisDataCommandServices;
    }

    public function index()
    {
        return view('pages.admin.approval.request-inventori.index');
    }

    public function changeStatusApproval(RequestInventoriUpdate $request, $id)
    {
        try {
            $data = $this->inventarisDataCommandServices->changeApprovalStatus($request, $id);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah status approval',
                'data' => [
                    'request_inventori' => $data,
                    // 'url' => route('admin.request-inventori.detail', $data->id),
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
