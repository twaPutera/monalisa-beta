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

        if ($request->has('statusArray')) {
            $peminjaman->whereIn('status', $request->statusArray);
        }

        if ($request->has('guid_peminjam_asset')) {
            $peminjaman->where('guid_peminjam_asset', $request->guid_peminjam_asset);
        }

        if (isset($request->limit)) {
            $peminjaman->limit($request->limit);
        }

        if (isset($request->orderby)) {
            $peminjaman->orderBy($request->orderby['field'], $request->orderby['sort']);
        }

        $peminjaman = $peminjaman->orderby('tanggal_peminjaman', 'DESC')->get();

        return $peminjaman;
    }

    public function countDataByGuidPeminjamAsset($guid_peminjam_asset)
    {
        $peminjaman = PeminjamanAsset::where('guid_peminjam_asset', $guid_peminjam_asset)->count();
        return $peminjaman;
    }
    public function findByIdAsset(string $id)
    {
        $peminjaman = PeminjamanAsset::query()
            ->wherehas('detail_peminjaman_asset', function ($query) use ($id) {
                $query->where('id_asset', $id);
            })
            ->where('status', 'diproses')
            ->first();
        return $peminjaman;
    }

    public function findById(string $id)
    {
        $peminjaman = PeminjamanAsset::query()->with(['request_peminjaman_asset.kategori_asset', 'detail_peminjaman_asset', 'approval', 'perpanjangan_peminjaman_asset'])->find($id);

        if (! isset($peminjaman)) {
            throw new Exception('Peminjaman Asset tidak ditemukan');
        }

        return $peminjaman;
    }
}
