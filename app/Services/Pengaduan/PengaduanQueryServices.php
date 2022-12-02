<?php

namespace App\Services\Pengaduan;

use App\Helpers\SsoHelpers;
use App\Models\LogPengaduanAsset;
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

        $user = SsoHelpers::getUserLogin();
        if (!isset($request->global)) {
            $pengaduan->with(['asset_data', 'asset_data.lokasi', 'image', 'lokasi']);
            if ($user) {
                if ($user->role == 'manager_it' || $user->role == "staff_it") {
                    $pengaduan->whereHas('asset_data', function ($query) use ($request) {
                        $query->where('is_it', '1');
                    });
                } else if ($user->role == 'manager_asset' || $user->role == "staff_asset") {
                    $pengaduan->whereHas('asset_data', function ($query) use ($request) {
                        $query->where('is_it', '0');
                    });
                }
            }
        }
        $pengaduan = $pengaduan->get();

        return $pengaduan;
    }

    public function findAllLog(Request $request)
    {
        $log_pengaduan = LogPengaduanAsset::query();
        if (isset($request->with)) {
            $log_pengaduan->with($request->with);
        }
        $log_pengaduan->orderBy('created_at', 'desc');
        $log_pengaduan->where('id_pengaduan', $request->id_pengaduan);
        $log_pengaduan = $log_pengaduan->get();
        return $log_pengaduan;
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
