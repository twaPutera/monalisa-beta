<?php

namespace App\Services\AssetService;

use App\Models\Service;
use Illuminate\Http\Request;

class AssetServiceQueryServices
{
    public function findAll()
    {
        return Service::all();
    }

    public function findById(string $id)
    {
        $data = Service::query()
            ->with(['detail_service', 'kategori_service', 'image'])
            ->where('id', $id)
            ->firstOrFail();

        $data->image = $data->image->map(function ($item) {
            $item->link = route('admin.listing-asset.service.image.preview') . '?filename=' . $item->path;
            return $item;
        });
        return $data;
    }

    public function findLastestLogByAssetId(string $id)
    {
        $services = Service::query()->with(['detail_service'])
            ->whereHas('detail_service', function ($query) use ($id) {
                $query->where('id_asset_data', $id);
            })
            ->where('status_service', 'done')
            ->orderby('created_at', 'desc')
            ->first();

        return $services;
    }

    public function getDataChartServices(Request $request)
    {
        $status_backlog = Service::query()
            ->where('status_service', 'backlog');

        $status_selesai = Service::query()
            ->where('status_service', 'selesai');

        $status_on_progress = Service::query()
            ->where('status_service', 'on progress');

        if (isset($request->year)) {
            $status_backlog->whereYear('created_at', $request->year);
            $status_selesai->whereYear('created_at', $request->year);
            $status_on_progress->whereYear('created_at', $request->year);
        }

        if (isset($request->month)) {
            $status_backlog->whereMonth('created_at', $request->month);
            $status_selesai->whereMonth('created_at', $request->month);
            $status_on_progress->whereMonth('created_at', $request->month);
        }

        $on_progress = $status_on_progress->count();
        $selesai = $status_selesai->count();
        $backlog = $status_backlog->count();

        $all = $on_progress + $selesai + $backlog;

        $all = $all == 0 ? 1 : $all;

        $data = [
            ($on_progress / $all) * 100,
            ($selesai / $all) * 100,
            ($backlog / $all) * 100,
        ];

        return $data;
    }
}
