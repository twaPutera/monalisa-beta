<?php

namespace App\Http\Controllers\User;

use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Pengaduan\PengaduanQueryServices;
use App\Services\Pengaduan\PengaduanCommandServices;
use App\Http\Requests\Pengaduan\PengaduanStoreRequest;
use App\Http\Requests\Pengaduan\PengaduanUpdateRequest;

class PengaduanController extends Controller
{
    protected $pengaduanCommandServices;
    protected $pengaduanQueryServices;
    public function __construct(
        PengaduanCommandServices $pengaduanCommandServices,
        PengaduanQueryServices $pengaduanQueryServices
    ) {
        $this->pengaduanCommandServices = $pengaduanCommandServices;
        $this->pengaduanQueryServices = $pengaduanQueryServices;
    }

    public function index()
    {
        return view('pages.user.pengaduan.index');
    }

    public function create()
    {
        return view('pages.user.pengaduan.create');
    }

    public function detail(string $id)
    {
        $pengaduan = $this->pengaduanQueryServices->findById($id);
        return view('pages.user.pengaduan.detail', compact('pengaduan'));
    }

    public function store(PengaduanStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $asset_service = $this->pengaduanCommandServices->storeUserPengaduan($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan pengaduan asset',
                'data' => $asset_service,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(PengaduanUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $asset_service = $this->pengaduanCommandServices->updateUserPengaduan($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah pengaduan asset',
                'data' => $asset_service,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getAllData(Request $request)
    {
        try {
            $pengaduan = $this->pengaduanQueryServices->findAll($request)->map(function ($item) {
                $item->link_detail = route('user.pengaduan.detail', $item->id);
                $item->tanggal_pengaduan = date('d/m/Y', strtotime($item->tanggal_pengaduan));
                return $item;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data pengaduan asset berhasil didapatkan',
                'data' => $pengaduan,
            ], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => 'Data pengaduan asset gagal didapatkan',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $pengaduan = $this->pengaduanQueryServices->findById($id);
        return view('pages.user.pengaduan.edit', compact('pengaduan'));
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $pengaduan = $this->pengaduanCommandServices->destroy($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data pengaduan asset berhasil dihapus',
                'data' => $pengaduan,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Data pengaduan asset berhasil dihapus',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function download(Request $request)
    {
        try {
            if ($request->status == 'request') {
                $path = storage_path('app/images/asset-pengaduan/' . $request->filename);
            } else {
                $path = storage_path('app/images/asset-respon-pengaduan/' . $request->filename);
            }
            $filename = $request->filename;
            $response = FileHelpers::downloadFile($path, $filename);

            return $response;
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
