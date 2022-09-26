<?php

namespace App\Services\InventarisData;

use App\Models\InventarisData;
use App\Http\Requests\InventarisData\InventarisDataStoreRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateRequest;
use App\Models\InventoriData;

class InventarisDataCommandServices
{
    public function store(InventarisDataStoreRequest $request)
    {
        $request->validated();

        $inventori_data = new InventoriData();
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->stok = $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        return $inventori_data;
    }

    public function update(string $id, InventarisDataUpdateRequest $request)
    {
        $request->validated();

        $inventori_data = InventoriData::findOrFail($id);
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->stok = $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        return $inventori_data;
    }

    public function delete(string $id)
    {
        $inventori_data = InventoriData::findOrFail($id);
        $inventori_data->delete();

        return $inventori_data;
    }
}
