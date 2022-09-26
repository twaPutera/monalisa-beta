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
        return InventoriData::findOrFail($id);
    }
}
