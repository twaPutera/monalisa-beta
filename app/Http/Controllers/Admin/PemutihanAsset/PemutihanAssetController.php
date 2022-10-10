<?php

namespace App\Http\Controllers\Admin\PemutihanAsset;

use App\Helpers\FileHelpers;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreDetailRequest;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\PemutihanAsset\PemutihanAssetQueryServices;
use App\Services\PemutihanAsset\PemutihanAssetCommandServices;
use App\Http\Requests\PemutihanAsset\PemutihanAssetStoreRequest;
use App\Services\PemutihanAsset\PemutihanAssetDatatableServices;
use App\Http\Requests\PemutihanAsset\PemutihanAssetUpdateRequest;

class PemutihanAssetController extends Controller
{
    protected $pemutihanAssetDatatableServices;
    protected $assetDataQueryServices;
    protected $pemutihanAssetCommandServices;
    protected $pemutihanAssetQueryServices;
    public function __construct(
        PemutihanAssetQueryServices $pemutihanAssetQueryServices,
        PemutihanAssetCommandServices $pemutihanAssetCommandServices,
        PemutihanAssetDatatableServices $pemutihanAssetDatatableServices,
        AssetDataQueryServices $assetDataQueryServices
    ) {
        $this->pemutihanAssetDatatableServices = $pemutihanAssetDatatableServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->pemutihanAssetCommandServices = $pemutihanAssetCommandServices;
        $this->pemutihanAssetQueryServices = $pemutihanAssetQueryServices;
    }
    public function index()
    {
        $total_asset = $this->pemutihanAssetQueryServices->findAll()->count();
        return view('pages.admin.pemutihan-asset.index', compact('total_asset'));
    }

    public function datatable(Request $request)
    {
        return $this->pemutihanAssetDatatableServices->datatable($request);
    }

    public function datatableAsset(Request $request)
    {
        return $this->pemutihanAssetDatatableServices->datatableAsset($request);
    }

    public function datatableDetail(Request $request)
    {
        return $this->pemutihanAssetDatatableServices->datatableDetail($request);
    }

    public function store(PemutihanAssetStoreRequest $request)
    {
        try {
            for ($i = 0; $i < count($request->id_checkbox); $i++) {
                $id_checkbox = $request->id_checkbox[$i];
                $findAssetWhere = $this->assetDataQueryServices->findById($id_checkbox);
                if ($findAssetWhere->is_pemutihan == 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Terdapat item asset yang sudah diputihkan sebelumnya',
                    ], 500);
                    break;
                }
            }
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data pemutihan asset',
                'data' => $pemutihan,
                'redirect' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function storeDetailUpdate(PemutihanAssetStoreDetailRequest $request, string $id)
    {
        try {
            if ($request->hasFile('gambar_asset')) {
                foreach ($request->file('gambar_asset') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $allowedfileExtension = ['jpeg', 'png', 'jpg', 'gif', 'svg'];
                    if (!in_array($extension, $allowedfileExtension)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Terdapat file yang tidak sesuai dengan format',
                        ], 500);
                        break;
                    }
                }
            }
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->storeDetailUpdate($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data pemutihan asset',
                'data' => $pemutihan,
                'redirect' => true,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function downloadBeritaAcara(Request $request)
    {
        try {
            $path = storage_path('app/file/pemutihan/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::downloadFile($path, $filename);
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function storeDetail(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id);
            return view('pages.admin.pemutihan-asset.components.page.detail', compact('pemutihan_asset'));
        } catch (Throwable $th) {
            return redirect()->route('admin.pemutihan-asset.index');
        }
    }

    public function storeDetailCancel(string $id)
    {
        try {
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->destroy($id);
            DB::commit();
            return redirect()->route('admin.pemutihan-asset.index');
        } catch (Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.pemutihan-asset.index');
        }
    }
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->destroy($id);
            DB::commit();
            if ($pemutihan) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghapus data pemutihan asset',
                    'data' => $pemutihan,
                    'redirect' => true
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data pemutihan asset',
            ], 500);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data pemutihan asset',
                'data' => $pemutihan_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function detail(string $id)
    {
        try {
            $pemutihan_asset = $this->pemutihanAssetQueryServices->findByIdDetail($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data detail pemutihan asset',
                'data' => $pemutihan_asset,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(PemutihanAssetUpdateRequest $request, string $id)
    {
        try {
            for ($i = 0; $i < count($request->id_checkbox); $i++) {
                $id_checkbox = $request->id_checkbox[$i];
                $findAssetWhere = $this->assetDataQueryServices->findById($id_checkbox);
                if ($findAssetWhere->is_pemutihan == 1) {
                    break;
                    return response()->json([
                        'success' => false,
                        'message' => 'Terdapat item asset yang sudah diputihkan sebelumnya',
                    ], 500);
                }
            }
            DB::beginTransaction();
            $pemutihan = $this->pemutihanAssetCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data pemutihan asset',
                'data' => $pemutihan,
            ], 200);
        } catch (Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
