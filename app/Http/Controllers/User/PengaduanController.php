<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class PengaduanController extends Controller
{
    public function index()
    {
        return view('pages.user.pengaduan.index');
    }

    public function create()
    {
        return view('pages.user.pengaduan.create');
    }
}
