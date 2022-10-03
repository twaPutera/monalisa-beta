<?php

namespace App\Services\InventarisData;

use App\Models\InventoriData;
use App\Http\Requests\InventarisData\InventarisDataStoreRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateStokRequest;
use App\Models\DetailInventoriData;

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
        $inventori_data->jumlah_saat_ini = $request->stok;
        $inventori_data->jumlah_sebelumnya = $request->stok;
        $inventori_data->harga_beli = $request->harga_beli;
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
        $inventori_data->harga_beli = $request->harga_beli;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        return $inventori_data;
    }

    public function updateStok(string $id, InventarisDataUpdateStokRequest $request)
    {
        $request->validated();

        $inventori_data = InventoriData::findOrFail($id);
        if ($request->jumlah_saat_ini > $inventori_data->jumlah_saat_ini) {
            $selisih = $request->jumlah_saat_ini - $inventori_data->jumlah_saat_ini;
            $status = "penambahan";
        } elseif ($request->jumlah_saat_ini < $inventori_data->jumlah_saat_ini) {
            $selisih =  $inventori_data->jumlah_saat_ini - $request->jumlah_saat_ini;
            $status = "pengurangan";
        }
        $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
        $inventori_data->jumlah_saat_ini = $request->jumlah_saat_ini;
        $inventori_data->save();

        $detailInventori = new DetailInventoriData();
        $detailInventori->id_inventori = $id;
        $detailInventori->no_memo = $request->no_memo;
        $detailInventori->jumlah = $selisih;
        $detailInventori->status = $status;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->save();
        return $inventori_data;
    }
}
