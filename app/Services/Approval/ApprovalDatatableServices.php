<?php

namespace App\Services\Approval;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Approval;

class ApprovalDatatableServices
{
    public function datatable(Request $request)
    {
        $query = Approval::query();

        if (isset($request->guid_approver)) {
            $query->where('guid_approver', $request->guid_approver);
        }

        if (isset($request->approvable_type)) {
            $query->where('approvable_type', $request->approvable_type);
        }

        if ($request->has('is_approve')) {
            $query->where('is_approve', $request->is_approve);
        }

        $query->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tipe_approval', function ($row) {
                return $row->approvalType();
            })
            ->addColumn('pembuat_approval', function ($row) {
                return $row->getPembuatApproval();
            })
            ->addColumn('link_detail', function ($item) {
                return $item->linkApproval();
            })
            ->addColumn('link_update', function ($item) {
                return $item->linkUpdateApproval();
            })
            ->rawColumns([])
            ->make(true);
    }
}
