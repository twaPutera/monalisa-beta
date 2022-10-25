<?php

namespace App\Http\Controllers\Admin\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryServiceController extends Controller
{
    public function index()
    {
        return view('pages.admin.report.service.index');
    }
}
