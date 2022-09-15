<?php

namespace App\Services\GroupKategoriAsset;

use App\Models\GroupKategoriAsset;
use Illuminate\Http\Request;

class GroupKategoriAssetQueryServices
{
    public function findAll()
    {
        return GroupKategoriAsset::query()->orderby('nama_group', 'asc')->get();
    }

    public function findById(string $id)
    {
        return GroupKategoriAsset::findOrFail($id);
    }

    public function findByKodeGroup(string $kodeGroup)
    {
        return GroupKategoriAsset::where('kode_group', $kodeGroup)->first();
    }

    public function getDataSelect2(Request $request)
    {
        $data = GroupKategoriAsset::query();

        if (isset($request->keyword)) {
            $data->where('nama_group', 'like', '%' . $request->keyword . '%');
        }

        $data = $data->orderby('nama_group', 'asc')
                ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_group,
            ];
        }

        return $results;
    }
}
