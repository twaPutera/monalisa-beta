<?php

namespace App\Services\Approval;

use App\Models\Approval;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

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
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<a href="#" onclick="edit(this)" class="btn mr-1 btn-sm btn-icon me-1 btn-primary">
                                <i class="fa fa-eye"></i>
                            </a>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
