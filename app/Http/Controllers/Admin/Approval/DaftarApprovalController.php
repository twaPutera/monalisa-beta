<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DaftarApprovalController extends Controller
{
    public function index()
    {
        return view('pages.admin.approval.daftar.index');
    }
}
