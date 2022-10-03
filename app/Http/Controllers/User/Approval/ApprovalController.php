<?php

namespace App\Http\Controllers\User\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        return view('pages.user.approval.index');
    }
}
