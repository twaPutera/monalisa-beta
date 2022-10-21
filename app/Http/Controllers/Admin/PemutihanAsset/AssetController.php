<?php

namespace App\Http\Controllers\Admin\PemutihanAsset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index()
    {
        return view('pages.admin.pemutihan-asset.asset.index');
    }
}
