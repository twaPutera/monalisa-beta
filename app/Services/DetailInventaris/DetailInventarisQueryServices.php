<?php

namespace App\Services\DetailInventaris;

use Illuminate\Http\Request;
use App\Models\Deta;
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
