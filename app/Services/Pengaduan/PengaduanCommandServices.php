<?php

namespace App\Services\Pengaduan;

use App\Models\AssetData;
use App\Models\Pengaduan;
use App\Models\AssetImage;
use App\Helpers\SsoHelpers;
use App\Helpers\FileHelpers;
use App\Models\LogPengaduanAsset;
use App\Http\Requests\Pengaduan\PengaduanStoreRequest;
use App\Http\Requests\Pengaduan\PengaduanUpdateRequest;

class PengaduanCommandServices
{
    public function storeUserPengaduan(PengaduanStoreRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();

        $asset_pengaduan = new Pengaduan();
        if (! empty($request->id_asset)) {
            $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $request->id_asset)->first();
            $asset_pengaduan->id_asset_data = $asset_data->id;
        }
        $asset_pengaduan->id_lokasi = $request->id_lokasi;
        $asset_pengaduan->tanggal_pengaduan  = $request->tanggal_pengaduan;
        $asset_pengaduan->catatan_pengaduan = $request->alasan_pengaduan;
        $asset_pengaduan->status_pengaduan = 'dilaporkan';
        $asset_pengaduan->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $asset_pengaduan->save();

        $log = self::storeLog($asset_pengaduan->id, 'dilaporkan', $request->alasan_pengaduan, 'Laporan Masuk');

        if ($request->hasFile('file_asset_service')) {
            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_pengaduan->id);
            $path = storage_path('app/images/asset-pengaduan');
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_pengaduan);
            $asset_images->imageable_id = $asset_pengaduan->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
    }

    public function updateUserPengaduan(PengaduanUpdateRequest $request, string $id)
    {
        $request->validated();

        $asset_pengaduan = Pengaduan::findOrFail($id);
        if (! empty($request->id_asset)) {
            $asset_data = AssetData::where('is_pemutihan', 0)->where('id', $request->id_asset)->first();
            $asset_pengaduan->id_asset_data = $asset_data->id;
        }
        $asset_pengaduan->id_lokasi = $request->id_lokasi;
        $asset_pengaduan->tanggal_pengaduan  = $request->tanggal_pengaduan;
        $asset_pengaduan->catatan_pengaduan = $request->alasan_pengaduan;
        $asset_pengaduan->status_pengaduan = 'dilaporkan';
        $asset_pengaduan->save();

        $log = self::storeLog($asset_pengaduan->id, 'dilaporkan', $request->alasan_pengaduan, 'Perubahan Laporan');

        if ($request->hasFile('file_asset_service')) {
            $path = storage_path('app/images/asset-pengaduan');
            if (isset($asset_pengaduan->image[0])) {
                $pathOld = $path . '/' . $asset_pengaduan->image[0]->path;
                FileHelpers::removeFile($pathOld);
                $asset_pengaduan->image[0]->delete();
            }

            $filename = self::generateNameImage($request->file('file_asset_service')->getClientOriginalExtension(), $asset_pengaduan->id);
            $filenamesave = FileHelpers::saveFile($request->file('file_asset_service'), $path, $filename);

            $asset_images = new AssetImage();
            $asset_images->imageable_type = get_class($asset_pengaduan);
            $asset_images->imageable_id = $asset_pengaduan->id;
            $asset_images->path = $filenamesave;
            $asset_images->save();
        }
    }

    protected static function generateNameImage($extension, $kodeasset)
    {
        $name = 'asset-pengaduan-' . $kodeasset . '-' . time() . '.' . $extension;
        return $name;
    }

    public function destroy(string $id)
    {
        $pengaduan = Pengaduan::with(['image'])->where('status_pengaduan', 'dilaporkan')->where('id', $id)->first();
        $path = storage_path('app/images/asset-pengaduan');
        if (isset($pengaduan->image[0])) {
            $pathOld = $path . '/' . $pengaduan->image[0]->path;
            FileHelpers::removeFile($pathOld);
            $pengaduan->image[0]->delete();
        }
        return $pengaduan->delete();
    }

    protected static function storeLog($id_pengaduan, $status, $message, $action)
    {
        $log_asset = new LogPengaduanAsset();
        $user = SsoHelpers::getUserLogin();
        $log_asset->id_pengaduan = $id_pengaduan;
        $log_asset->message_log = "$action Data Pengaduan Asset/Lokasi ($message)";
        $log_asset->status =  $status;
        $log_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log_asset->save();

        return $log_asset;
    }
}
