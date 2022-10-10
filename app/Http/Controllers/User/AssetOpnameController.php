<?php

namespace App\Http\Controllers\User;

use Throwable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\StatusAssetDataHelpers;
use App\Services\AssetData\AssetDataQueryServices;
use App\Services\AssetOpname\AssetOpnameCommandServices;
use App\Http\Requests\AssetOpname\AssetOpnameStoreRequest;

class AssetOpnameController extends Controller
{
    protected $assetDataQueryServices;
    protected $assetOpnameCommandServices;
    public function __construct(
        AssetDataQueryServices $assetDataQueryServices,
        AssetOpnameCommandServices $assetOpnameCommandServices
    ) {
        $this->assetDataQueryServices = $assetDataQueryServices;
        $this->assetOpnameCommandServices = $assetOpnameCommandServices;
    }
    public function create(string $id)
    {
        $list_status = StatusAssetDataHelpers::listStatusAssetData();
        $asset_data = $this->assetDataQueryServices->findById($id);
        return view('pages.user.asset.opname.create', compact('asset_data', 'list_status'));
    }

    public function store(AssetOpnameStoreRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $data =  $this->assetOpnameCommandServices->store($request, $id);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan opname',
                'data' => $data,
            ], 200);
        } catch (Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
