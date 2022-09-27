<?php

namespace App\Services\DetailInventaris;

use App\Models\DetailInventoriData;
use App\Http\Requests\DetailInventaris\DetailInventarisStoreRequest;
use App\Http\Requests\DetailInventaris\DetailInventarisUpdateRequest;

class DetailInventarisCommandServices
{
    public function store(DetailInventarisStoreRequest $request, string $id_inventaris)
    {
        $request->validated();

        $inventori_data = new DetailInventoriData();
        $inventori_data->id_inventori = $id_inventaris;
        $inventori_data->id_lokasi = $request->id_lokasi;
        $inventori_data->stok = $request->stok;
        $inventori_data->keterangan = $request->keterangan;
        $inventori_data->save();

        return $inventori_data;
    }

    public function update(string $id, DetailInventarisUpdateRequest $request)
    {
        $request->validated();

        $inventori_data = DetailInventoriData::findOrFail($id);
        $inventori_data->id_lokasi = $request->id_lokasi;
        $inventori_data->stok = $request->stok;
        $inventori_data->keterangan = $request->keterangan;
        $inventori_data->save();

        return $inventori_data;
    }

    public function delete(string $id)
    {
        $inventori_data = DetailInventoriData::findOrFail($id);
        $inventori_data->delete();

        return $inventori_data;
    }
}
