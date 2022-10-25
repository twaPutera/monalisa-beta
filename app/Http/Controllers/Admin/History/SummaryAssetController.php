<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SummaryAssetController extends Controller
{
    public function index(){
        return view('pages.admin.report.asset.index');
    }
}
