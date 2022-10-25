<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryPengaduanController extends Controller
{
    public function index()
    {
        return view('pages.admin.report.asset.index');
    }
}
