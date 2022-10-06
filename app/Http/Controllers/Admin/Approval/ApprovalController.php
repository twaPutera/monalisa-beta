<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Approval\ApprovalCommandServices;
use App\Services\Approval\ApprovalDatatableServices;
use App\Services\Approval\ApprovalQueryServices;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    protected $approvalQueryServices;
    protected $approvalCommandServices;
    protected $approvalDatatableServices;

    public function __construct(
        ApprovalQueryServices $approvalQueryServices,
        ApprovalCommandServices $approvalCommandServices,
        ApprovalDatatableServices $approvalDatatableServices
    ) {
        $this->approvalQueryServices = $approvalQueryServices;
        $this->approvalCommandServices = $approvalCommandServices;
        $this->approvalDatatableServices = $approvalDatatableServices;
    }

    public function datatable(Request $request)
    {
        return $this->approvalDatatableServices->datatable($request);
    }
}
