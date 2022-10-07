<?php

namespace App\Services\PeminjamanAsset;

use Exception;
use App\Models\PeminjamanAsset;
use App\Models\AssetData;
use Illuminate\Http\Request;

class PeminjamanAssetQueryServices
{
    public function findAll(Request $request)
    {
        $peminjaman = PeminjamanAsset::query();

        if (isset($request->with)) {
            $peminjaman->with($request->with);
        }

        if ($request->has('status')) {
            $peminjaman->where('status', $request->status);
        }

        if ($request->has('guid_peminjam_asset')) {
            $peminjaman->where('guid_peminjam_asset', $request->guid_peminjam_asset);
        }

        $peminjaman = $peminjaman->orderby('tanggal_peminjaman', 'DESC')->get();

        return $peminjaman;
    }
}
