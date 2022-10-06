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

        if (isset($request->is_approve)) {
            $query->where('is_approve', $request->is_approve);
        }

        $query->orderBy('created_at', 'ASC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tipe_approval', function ($row) {
                return $row->approvalType();
            })
            ->addColumn('action', function ($item) {
                $element = '';
                $element .= '<button type="button" onclick="edit(this)" data-url_edit="' . route('admin.setting.group-kategori-asset.edit', $item->id) . '" data-url_update="' . route('admin.setting.group-kategori-asset.update', $item->id) . '" class="btn mr-1 btn-sm btn-icon me-1 btn-warning">
                                <i class="fa fa-edit"></i>
                            </button>';
                return $element;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
