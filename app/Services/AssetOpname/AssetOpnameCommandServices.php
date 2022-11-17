<?php

namespace App\Services\AssetOpname;

use App\Models\AssetData;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\LogAssetOpname;
use App\Models\PerencanaanServices;
use App\Http\Requests\AssetOpname\AssetOpnameStoreRequest;

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

        $perencanan_services = new PerencanaanServices();
        $perencanan_services->id_asset_data = $asset_data->id;
        $perencanan_services->status = 'perencanaan';
        $perencanan_services->tanggal_perencanaan = $request->tanggal_services;
        $perencanan_services->keterangan = $request->keterangan_services;
        $perencanan_services->id_log_opname = $opname_log->id;
        $perencanan_services->save();

        if ($request->hasFile('gambar_asset')) {
            $path = storage_path('app/images/asset');
            if (isset($asset_data->image[0])) {
                $pathOld = $path . '/' . $asset_data->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset_data->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('gambar_asset')->getClientOriginalExtension(), $asset_data->kode_asset);
            $filenamesave = FileHelpers::saveFile($request->file('gambar_asset'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_data);
            $asset_images->imageable_id = $asset_data->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $opname_log;
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
}
