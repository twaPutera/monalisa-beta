<?php

namespace App\Services\Pengaduan;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanQueryServices
{
    public function findAll(Request $request)
    {
        $pengaduan = Pengaduan::query();

        if (isset($request->with)) {
            $pengaduan->with($request->with);
        }

        if ($request->has('status_pengaduan')) {
            $pengaduan->where('status_pengaduan', $request->status_pengaduan);
        }

        if ($request->has('created_by')) {
            $pengaduan->where('created_by', $request->created_by);
        }

        if (isset($request->limit)) {
            $pengaduan->limit($request->limit);
        }

        $pengaduan = $pengaduan->orderby('tanggal_pengaduan', 'DESC')->get();

        return $pengaduan;
    }

    public function findById(string $id)
    {
        return Pengaduan::with(['image' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
    }
}
