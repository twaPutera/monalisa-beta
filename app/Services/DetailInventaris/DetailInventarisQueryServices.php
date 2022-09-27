<?php

namespace App\Services\DetailInventaris;

use App\Models\DetailInventoriData;

class DetailInventarisQueryServices
{
    public function findAll()
    {
        return DetailInventoriData::all();
    }

    public function findById(string $id)
    {
        return DetailInventoriData::findOrFail($id);
    }
}
