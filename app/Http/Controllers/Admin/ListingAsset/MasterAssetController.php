<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterAssetController extends Controller
{
    public function index()
    {
        return view('pages.admin.listing-asset.index');
    }
}
