<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BahanHabisPakaiController extends Controller
{
    public function index()
    {
        return view('pages.user.asset.bahan-habis-pakai.index');
    }
    public function create()
    {
        return view('pages.user.asset.bahan-habis-pakai.create');
    }
}
