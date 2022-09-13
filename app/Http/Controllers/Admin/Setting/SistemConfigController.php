<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SistemConfig\SistemConfigQueryServices;
use App\Services\SistemConfig\SistemConfigCommandServices;
use App\Http\Requests\SistemConfig\SistemConfigUpdateRequest;
use Illuminate\Support\Facades\DB;

class SistemConfigController extends Controller
{
    private $sistemConfigQueryServices;
    private $sistemConfigCommandServices;

    public function __construct(
        SistemConfigQueryServices $sistemConfigQueryServices,
        SistemConfigCommandServices $sistemConfigCommandServices
    ) {
        $this->sistemConfigQueryServices = $sistemConfigQueryServices;
        $this->sistemConfigCommandServices = $sistemConfigCommandServices;
    }

    public function index()
    {
        $sistemConfigs = $this->sistemConfigQueryServices->findAll();
        return view('pages.admin.settings.config.index', compact('sistemConfigs'));
    }

    public function update(SistemConfigUpdateRequest $request)
    {
        return $request;
        DB::beginTransaction();
        try {
            $this->sistemConfigCommandServices->update($request, $id);
            DB::commit();
            return redirect()->route('admin.setting.sistem-config.index')->with('success', 'Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.setting.sistem-config.index')->with('error', 'Data gagal diubah');
        }
    }
}
