<?php

namespace App\Services\Lokasi;

use App\Models\Lokasi;
use App\Http\Requests\Lokasi\LokasiStoreRequest;
use App\Http\Requests\Lokasi\LokasiUpdateRequest;

class LokasiCommandServices
{
    public function store(LokasiStoreRequest $request)
    {
        $request->validated();

        $lokasi = new Lokasi();
        $lokasi->id_parent_lokasi = $request->id_parent_lokasi === 'root' ? null : $request->id_parent_lokasi;
        $lokasi->kode_lokasi = $request->kode_lokasi;
        $lokasi->nama_lokasi = $request->nama_lokasi;
        $lokasi->keterangan = $request->keterangan;
        $lokasi->save();

        return $lokasi;
    }

    public function update(LokasiUpdateRequest $request, $id)
    {
        $request->validated();

        $lokasi = Lokasi::findOrFail($id);
        $lokasi->id_parent_lokasi = $request->id_parent_lokasi;
        $lokasi->kode_lokasi = $request->kode_lokasi;
        $lokasi->nama_lokasi = $request->nama_lokasi;
        $lokasi->keterangan = $request->keterangan;
        $lokasi->save();

        return $lokasi;
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->delete();

        return $lokasi;
    }
}
