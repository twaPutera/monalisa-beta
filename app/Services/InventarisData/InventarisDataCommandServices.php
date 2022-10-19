<?php

namespace App\Services\InventarisData;

use App\Models\InventoriData;
use App\Models\LogPenambahanInventori;
use App\Models\LogPenguranganInventori;
use App\Http\Requests\InventarisData\InventarisDataStoreRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateRequest;
use App\Http\Requests\InventarisData\InventarisDataUpdateStokRequest;
use App\Http\Requests\InventarisData\InventarisDataStoreUpdateRequest;
use App\Helpers\SsoHelpers;

class InventarisDataCommandServices
{
    public function store(InventarisDataStoreRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $inventori_data = new InventoriData();
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->jumlah_saat_ini = $request->stok;
        $inventori_data->jumlah_sebelumnya = $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        $detailInventori = new LogPenambahanInventori();
        $detailInventori->id_inventori = $inventori_data->id;
        $detailInventori->jumlah = $request->stok;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->harga_beli = $request->harga_beli;
        $detailInventori->created_by = $user->name;
        $detailInventori->save();

        return $inventori_data;
    }

    public function storeUpdate(InventarisDataStoreUpdateRequest $request)
    {
        $request->validated();

        $user = SsoHelpers::getUserLogin();
        $inventori_data = InventoriData::findOrFail($request->id_inventaris);
        $inventori_data->id_kategori_inventori = $request->id_kategori_inventori;
        $inventori_data->id_satuan_inventori = $request->id_satuan_inventori;
        $inventori_data->kode_inventori = $request->kode_inventori;
        $inventori_data->nama_inventori = $request->nama_inventori;
        $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
        $inventori_data->jumlah_saat_ini = $inventori_data->jumlah_saat_ini + $request->stok;
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        $detailInventori = new LogPenambahanInventori();
        $detailInventori->id_inventori = $inventori_data->id;
        $detailInventori->jumlah = $request->stok;
        $detailInventori->tanggal = $request->tanggal;
        $detailInventori->harga_beli = $request->harga_beli;
        $detailInventori->created_by = $user->name;
        $detailInventori->save();

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
        $inventori_data->deskripsi_inventori = $request->deskripsi_inventori;
        $inventori_data->save();

        return $inventori_data;
    }

    public function updateStok(string $id, InventarisDataUpdateStokRequest $request)
    {
        $request->validated();
        $user = SsoHelpers::getUserLogin();
        $inventori_data = InventoriData::findOrFail($id);
        $selisih = $inventori_data->jumlah_saat_ini - $request->jumlah_keluar;
        if ($selisih >= 0) {
            $inventori_data->jumlah_sebelumnya = $inventori_data->jumlah_saat_ini;
            $inventori_data->jumlah_saat_ini = $selisih;
            $inventori_data->save();

            $detailInventori = new LogPenguranganInventori();
            $detailInventori->id_inventori = $id;
            $detailInventori->no_memo = $request->no_memo;
            $detailInventori->jumlah = $request->jumlah_keluar;
            $detailInventori->tanggal = $request->tanggal;
            $detailInventori->created_by = $user->name;
            $detailInventori->save();
            return $inventori_data;
        }
        return false;
    }
}
