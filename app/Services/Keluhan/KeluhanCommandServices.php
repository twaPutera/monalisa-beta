<?php

namespace App\Services\Keluhan;

use App\Helpers\SsoHelpers;
use App\Models\Pengaduan;
use App\Http\Requests\Keluhan\KeluhanUpdateRequest;
use App\Models\LogPengaduanAsset;
use Illuminate\Support\Facades\Auth;

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

        return $asset_pengaduan;
    }

    protected static function storeLog($id_pengaduan,  $status, $message)
    {
        $log_asset = new LogPengaduanAsset();
        $user = SsoHelpers::getUserLogin();
        $log_asset->id_pengaduan = $id_pengaduan;
        $log_asset->message_log = "Perubahan data ($message)";
        $log_asset->status =  $status;
        $log_asset->created_by = config('app.sso_siska') ? $user->guid : $user->id;
        $log_asset->save();

        return $log_asset;
    }
}
