<?php

namespace App\Http\Controllers\Admin\ListingAsset;

use App\Helpers\FileHelpers;
use Illuminate\Http\Request;
use App\Imports\DataAssetImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MasterDataAssetExport;
use App\Helpers\StatusAssetDataHelpers;
use App\Services\UserSso\UserSsoQueryServices;
use App\Http\Requests\AssetData\AssetStoreRequest;
use App\Services\AssetData\AssetDataQueryServices;
use App\Http\Requests\AssetData\AssetImportRequest;
use App\Http\Requests\AssetData\AssetUpdateRequest;
use App\Services\AssetData\AssetDataCommandServices;
use App\Services\AssetData\AssetDataDatatableServices;
use App\Services\AssetOpname\AssetOpnameQueryServices;
use App\Services\AssetService\AssetServiceQueryServices;

class MasterAssetController extends Controller
{
    protected $assetDataCommandServices;
    protected $assetDataDatatableServices;
    protected $assetDataQueryServices;
    protected $userSsoQueryServices;
    protected $assetServiceQueryServices;
    protected $assetOpnameQueryServices;
    public function __construct(
        AssetDataCommandServices $assetDataCommandServices,
        AssetDataDatatableServices $assetDataDatatableServices,
        AssetDataQueryServices $assetDataQueryServices,
        UserSsoQueryServices $userSsoQueryServices,
        AssetServiceQueryServices $assetServiceQueryServices,
        assetOpnameQueryServices $assetOpnameQueryServices
    ) {
        $this->assetDataCommandServices = $assetDataCommandServices;
        $this->assetDataDatatableServices = $assetDataDatatableServices;
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->userSsoQueryServices = $userSsoQueryServices;
        $this->assetServiceQueryServices = $assetServiceQueryServices;
        $this->assetOpnameQueryServices = $assetOpnameQueryServices;
    }

    public function index()
    {
        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        return view('pages.admin.listing-asset.index', compact('list_status'));
    }

    public function datatable(Request $request)
    {
        return $this->assetDataDatatableServices->datatable($request);
    }

    public function store(AssetStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->store($request);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan data asset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function show($id)
    {
        try {
            $data = $this->assetDataQueryServices->findById($id);
            $service = $this->assetServiceQueryServices->findLastestLogByAssetId($id);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data asset',
                'data' => [
                    'asset' => $data,
                    'service' => $service,
                ],
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function detail($id)
    {
        $asset = $this->assetDataQueryServices->findById($id);
        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        return view('pages.admin.listing-asset.detail', compact('asset', 'list_status'));
    }

    public function update(AssetUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $this->assetDataCommandServices->update($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil mengubah data asset',
                'form' => 'editAsset',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function previewImage(Request $request)
    {
        try {
            $path = storage_path('app/images/asset/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::viewFile($path, $filename);

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

    public function previewQr(Request $request)
    {
        try {
            $path = storage_path('app/images/qr-code/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::viewFile($path, $filename);

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

    public function downloadQr(Request $request)
    {
        try {
            $path = storage_path('app/images/qr-code/' . $request->filename);
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

    public function downloadTemplateImport()
    {
        return Excel::download(new MasterDataAssetExport, 'template-import-asset.xlsx');
    }

    public function getDataAllOwnerSelect2(Request $request)
    {
        try {
            $response = $this->userSsoQueryServices->getUserSso($request);
            $data = collect($response)->map(function ($item) {
                return [
                    'id' => $item['guid'],
                    'text' => $item['name'] . ' - ' . $item['email'],
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data owner',
                'data' => $data,
            ]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function getDataAllAssetSelect2(Request $request)
    {
        try {
            $data = $this->assetDataQueryServices->getDataAssetSelect2($request);
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function importAssetData(AssetImportRequest $request)
    {
        try {
            DB::beginTransaction();
            $response = Excel::import(new DataAssetImport(), $request->file('file'));
            DB::commit();
            return response()->json([
                'success' => true,
                'form' => 'import',
                'message' => 'Berhasil mengimport data asset',
                // 'data' => $response,
            ], 200);
            //code...
        } catch (\Maatwebsite\Excel\Validators\ValidationException $th) {
            DB::rollback();
            $failures = $th->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }
            return response()->json([
                'success' => false,
                'form' => 'import',
                'message' => $th->getMessage(),
                'errors' => $errors,
            ], 400);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'form' => 'import',
                'message' => $th->getMessage(),
            ], 400);
        }
    }

    public function log_asset_dt(Request $request)
    {
        $response = $this->assetDataDatatableServices->log_asset_dt($request);
        return $response;
    }

    public function log_opname_dt(Request $request)
    {
        $response = $this->assetDataDatatableServices->log_opname_dt($request);
        return $response;
    }

    public function log_opname_show($id)
    {
        try {
            $data = $this->assetOpnameQueryServices->findById($id);
            //code...
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menampilkan data image',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function previewImageOpname(Request $request)
    {
        try {
            $path = storage_path('app/images/asset-opname/' . $request->filename);
            $filename = $request->filename;
            $response = FileHelpers::viewFile($path, $filename);

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
