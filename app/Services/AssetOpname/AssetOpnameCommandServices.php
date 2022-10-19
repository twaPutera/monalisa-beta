<?php

namespace App\Services\AssetOpname;

use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\FileHelpers;
use App\Models\LogAssetOpname;
use App\Http\Requests\AssetOpname\AssetOpnameStoreRequest;
use Illuminate\Support\Facades\Session;
use App\Helpers\SsoHelpers;

class AssetOpnameCommandServices
{
    public function store(AssetOpnameStoreRequest $request, string $id)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $id)->first();
        $opname_log = new LogAssetOpname();
        $opname_log->id_asset_data = $asset_data->id;
        $opname_log->tanggal_opname = $request->tanggal_opname;
        $opname_log->status_awal = $asset_data->status_kondisi;
        $opname_log->status_akhir = $request->status_kondisi;
        $opname_log->akuntan_awal = $asset_data->status_akunting;
        $opname_log->akuntan_akhir = $request->status_akunting;
        $opname_log->keterangan = $request->catatan;
        $opname_log->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $opname_log->save();

        $asset_data->status_kondisi = $request->status_kondisi;
        $asset_data->status_akunting = $request->status_akunting;
        $asset_data->save();
        if ($request->hasFile('gambar_asset')) {
            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $opname_log->id);
            $path = storage_path('app/images/asset-opname');
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($opname_log);
            $asset_images->imageable_id = $opname_log->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $opname_log;
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-opname-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
}
