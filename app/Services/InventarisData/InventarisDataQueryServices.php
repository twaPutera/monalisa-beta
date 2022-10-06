<?php

namespace App\Services\InventarisData;

use Illuminate\Http\Request;
use App\Models\InventoriData;

class InventarisDataQueryServices
{
    public function findAll()
    {
        return InventoriData::all();
    }

    public function findById(string $id)
    {
        return InventoriData::with(['satuan_inventori', 'kategori_inventori'])->findOrFail($id);
    }

    public function getDataSelect2(Request $request)
    {
        $data = InventoriData::query();

        if (isset($request->keyword)) {
            $data->where('nama_inventori', 'like', '%' . $request->keyword . '%')
                ->where(function ($query) use ($request) {
                    $query->orWhere('kode_inventori', 'like', '%' . $request->keyword . '%');
                });
        }

        $data = $data->orderby('nama_inventori', 'asc')
            ->get();

        $results = [];
        foreach ($data as $item) {
            $results[] = [
                'id' => $item->id,
                'text' => $item->nama_inventori . ' (' . $item->kode_inventori . ')',
            ];
        }

        return $results;
    }
}
