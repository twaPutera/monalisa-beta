<?php

namespace App\Http\Controllers\Admin\Inventaris;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\InventarisData\InventarisDataQueryServices;
use App\Services\DetailInventaris\DetailInventarisQueryServices;
use App\Services\DetailInventaris\DetailInventarisCommandServices;
use App\Http\Requests\DetailInventaris\DetailInventarisStoreRequest;
use App\Services\DetailInventaris\DetailInventarisDatatableServices;
use App\Http\Requests\DetailInventaris\DetailInventarisUpdateRequest;

class DetailInventarisController extends Controller
{
    protected $detailInventarisCommandServices;
    protected $detailInventarisQueryServices;
    protected $inventarisDataQueryServices;
    protected $detailInventarisDatatableServices;

    public function __construct(
        DetailInventarisCommandServices $detailInventarisCommandServices,
        DetailInventarisQueryServices $detailInventarisQueryServices,
        InventarisDataQueryServices $inventarisDataQueryServices,
        DetailInventarisDatatableServices $detailInventarisDatatableServices
    ) {
        $this->detailInventarisCommandServices = $detailInventarisCommandServices;
        $this->detailInventarisDatatableServices = $detailInventarisDatatableServices;
        $this->detailInventarisQueryServices = $detailInventarisQueryServices;
        $this->inventarisDataQueryServices = $inventarisDataQueryServices;
    }

    public function index(string $id_inventaris)
    {
        $inventaris = $this->inventarisDataQueryServices->findById($id_inventaris);
        return view('pages.admin.detail-inventaris.index', compact('inventaris'));
    }

    public function datatable(Request $request)
    {
        return $this->detailInventarisDatatableServices->datatable($request);
    }

    public function store(DetailInventarisStoreRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->detailInventarisCommandServices->store($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data detail inventaris',
                'data' => $listing_inventaris,
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

    public function edit(string $id_data)
    {
        try {
            $listing_inventaris = $this->detailInventarisQueryServices->findById($id_data);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data inventaris',
                'data' => $listing_inventaris,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function update(DetailInventarisUpdateRequest $request, string $id_data)
    {
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->detailInventarisCommandServices->update($id_data, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data detail inventaris',
                'data' => $listing_inventaris,
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

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->detailInventarisCommandServices->delete($id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus data inventaris',
                'data' => $listing_inventaris,
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
}
