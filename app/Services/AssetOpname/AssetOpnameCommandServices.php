<?php

namespace App\Services\AssetOpname;

use App\Helpers\FileHelpers;
use App\Http\Requests\AssetOpname\AssetOpnameStoreRequest;
use App\Models\AssetData;
use App\Models\AssetImage;
use App\Models\LogAssetOpname;

class AssetOpnameCommandServices
{
    public function store(AssetOpnameStoreRequest $request, string $id)
    {
        $request->validated();

        $asset_data = AssetData::findOrFail($id);
        $opname_log = new LogAssetOpname();
        $opname_log->id_asset_data = $asset_data->id;
        $opname_log->tanggal_opname = $request->tanggal_opname;
        $opname_log->status_awal = $asset_data->status_kondisi;
        $opname_log->status_akhir = $request->status_kondisi;
        $opname_log->keterangan = $request->catatan;
        $opname_log->save();

        $asset_data->status_kondisi = $request->status_kondisi;
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
