<?php

namespace App\Services\Keluhan;

use App\Models\Pengaduan;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\LogPengaduanAsset;
use App\Http\Requests\Keluhan\KeluhanUpdateRequest;

class KeluhanCommandServices
{
    public function update(KeluhanUpdateRequest $request, string $id)
    {
        $request->validated();
        $asset_pengaduan = Pengaduan::findOrFail($id);
        $asset_pengaduan->status_pengaduan = $request->status_pengaduan;
        $asset_pengaduan->catatan_admin = $request->catatan_admin;
        $asset_pengaduan->save();

        $log = self::storeLog($asset_pengaduan->id, $request->status_pengaduan, $request->catatan_admin);

        if ($request->hasFile('file_pendukung')) {
            $path = storage_path('app/images/asset-respon-pengaduan');
            if (isset($asset_pengaduan->image[1])) {
                $pathOld = $path . '/' . $asset_pengaduan->image[1]->path;
                FileHelpers::removeFile($pathOld);
                $asset_pengaduan->image[1]->delete();
            }

            $filename = self::generateNameImage($request->file('file_pendukung')->getClientOriginalExtension(), $asset_pengaduan->id);
            $filenamesave = FileHelpers::saveFile($request->file('file_pendukung'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_pengaduan);
            $asset_images->imageable_id = $asset_pengaduan->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
        return $asset_pengaduan;
    }

    protected static function storeLog($id_pengaduan, $status, $message)
    {
        $log_asset = new LogPengaduanAsset();
        $user = SsoHelpers::getUserLogin();
        $log_asset->id_pengaduan = $id_pengaduan;
        $log_asset->message_log = "Penanganan Pengaduan ($message)";
        $log_asset->status =  $status;
        $log_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log_asset->save();

        return $log_asset;
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-respon-pengaduan-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }
}
