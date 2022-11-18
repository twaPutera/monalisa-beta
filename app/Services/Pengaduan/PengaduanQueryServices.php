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

        if (isset($request->arrayStatus)) {
            $pengaduan->whereIn('status_pengaduan', $request->arrayStatus);
        }

        if ($request->has('created_by')) {
            $pengaduan->where('created_by', $request->created_by);
        }

        if (isset($request->limit)) {
            $pengaduan->limit($request->limit);
        }

        if (isset($request->orderby)) {
            $pengaduan->orderBy($request->orderby['field'], $request->orderby['sort']);
        } else {
            $pengaduan->orderBy('tanggal_pengaduan', 'desc');
        }

        $pengaduan = $pengaduan->get();

        return $pengaduan;
    }

    public function countDataByCreatedById($created_by)
    {
        $pengaduan = Pengaduan::where('created_by', $created_by)->count();
        return $pengaduan;
    }

    public function findById(string $id)
    {
        return Pengaduan::with(['image' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])->findOrFail($id);
    }
}
