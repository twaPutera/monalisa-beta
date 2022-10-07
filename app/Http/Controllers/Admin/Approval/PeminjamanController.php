<?php

namespace App\Http\Controllers\Admin\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        return view('pages.admin.approval.peminjaman.index');
    }
}
