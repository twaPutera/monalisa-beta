<?php

namespace App\Http\Controllers\Admin\Inventaris;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\InventarisData\InventarisDataQueryServices;
use App\Services\InventarisData\InventarisDataCommandServices;
use App\Http\Requests\InventarisData\InventarisDataStoreRequest;
use App\Services\InventarisData\InventarisDataDatatableServices;
use App\Http\Requests\InventarisData\InventarisDataUpdateRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateStokRequest;

class MasterInventarisController extends Controller
{
    protected $inventarisDataCommandServices;
    protected $inventarisDataQueryServices;
    protected $inventarisDataDatatableServices;

    public function __construct(
        InventarisDataCommandServices $inventarisDataCommandServices,
        InventarisDataQueryServices $inventarisDataQueryServices,
        InventarisDataDatatableServices $inventarisDataDatatableServices
    ) {
        $this->inventarisDataCommandServices = $inventarisDataCommandServices;
        $this->inventarisDataDatatableServices = $inventarisDataDatatableServices;
        $this->inventarisDataQueryServices = $inventarisDataQueryServices;
    }

    public function index()
    {
        return view('pages.admin.listing-inventaris.index');
    }

    public function datatable(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatable($request);
    }

    public function store(InventarisDataStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->inventarisDataCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data inventaris',
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

    public function edit(string $id)
    {
        try {
            $listing_inventaris = $this->inventarisDataQueryServices->findById($id);

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

    public function editStok(string $id)
    {
        try {
            $listing_inventaris = $this->inventarisDataQueryServices->findById($id);
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengambil data stok inventaris',
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

    public function update(InventarisDataUpdateRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->inventarisDataCommandServices->update($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data inventaris',
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

    public function updateStok(InventarisDataUpdateStokRequest $request, $id)
    {
        $request->request->add(['id' => $id]);
        try {
            DB::beginTransaction();
            $listing_inventaris = $this->inventarisDataCommandServices->updateStok($id, $request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data inventaris',
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

    public function detail(string $id)
    {
        try {
            $listing_inventaris = $this->inventarisDataQueryServices->findById($id);
            return view('pages.admin.listing-inventaris.components.data._data_modal_detail', compact('listing_inventaris'));
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function datatableStok(Request $request)
    {
        return $this->inventarisDataDatatableServices->datatableStok($request);
    }
}
