<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroupKategoriAsset\GroupKategoriAssetQueryServices;
use App\Services\GroupKategoriAsset\GroupKategoriAssetCommandServices;
use App\Services\GroupKategoriAsset\GroupKategoriAssetDatatableServices;
use App\Http\Requests\GroupKategoriAsset\GroupKategoriAssetStoreRequest;
use App\Http\Requests\GroupKategoriAsset\GroupKategoriAssetUpdateRequest;
use Illuminate\Support\Facades\DB;

class GroupKategoriAssetController extends Controller
{
    protected $groupKategoriAssetQueryServices;
    protected $groupKategoriAssetCommandServices;
    protected $groupKategoriAssetDatatableServices;

    public function __construct(
        GroupKategoriAssetQueryServices $groupKategoriAssetQueryServices,
        GroupKategoriAssetCommandServices $groupKategoriAssetCommandServices,
        GroupKategoriAssetDatatableServices $groupKategoriAssetDatatableServices
    ) {
        $this->groupKategoriAssetQueryServices = $groupKategoriAssetQueryServices;
        $this->groupKategoriAssetCommandServices = $groupKategoriAssetCommandServices;
        $this->groupKategoriAssetDatatableServices = $groupKategoriAssetDatatableServices;
    }

    public function index()
    {
        return view('pages.admin.settings.group-kategori-asset.index');
    }

    public function datatable(Request $request)
    {
        return $this->groupKategoriAssetDatatableServices->datatable($request);
    }

    public function store(GroupKategoriAssetStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->groupKategoriAssetCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data group kategori asset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(string $id)
    {
        try {
            $data = $this->groupKategoriAssetQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(GroupKategoriAssetUpdateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->groupKategoriAssetCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data group kategori asset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->groupKategoriAssetCommandServices->destroy($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data group kategori asset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
