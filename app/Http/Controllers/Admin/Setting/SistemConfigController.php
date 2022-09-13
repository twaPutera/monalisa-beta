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
        DB::beginTransaction();
        try {
            $data = $this->sistemConfigCommandServices->updateAll($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data config',
                'data' => $data,
            ], 200);
        } catch (\Exception $th) {
            // throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
