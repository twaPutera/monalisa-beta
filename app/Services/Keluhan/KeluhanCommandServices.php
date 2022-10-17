<?php

namespace App\Services\Keluhan;

use App\Http\Requests\Keluhan\KeluhanUpdateRequest;
use App\Models\Pengaduan;
use Exception;

class KeluhanCommandServices
{
    public function update(KeluhanUpdateRequest $request, string $id)
    {
        $request->validated();
        $asset_pengaduan = Pengaduan::findOrFail($id);
        $asset_pengaduan->status_pengaduan = $request->status_pengaduan;
        $asset_pengaduan->catatan_admin = $request->catatan_admin;
        $asset_pengaduan->save();
    }
}
