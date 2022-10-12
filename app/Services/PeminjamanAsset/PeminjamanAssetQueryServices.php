<?php

namespace App\Services\PeminjamanAsset;

use Illuminate\Http\Request;
use App\Models\PeminjamanAsset;

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

    public function findById(string $id)
    {
        $peminjaman = PeminjamanAsset::query()->with(['request_peminjaman_asset.kategori_asset', 'detail_peminjaman_asset', 'approval'])->find($id);

        if (! isset($peminjaman)) {
            throw new Exception('Peminjaman Asset tidak ditemukan');
        }

        return $peminjaman;
    }
}
