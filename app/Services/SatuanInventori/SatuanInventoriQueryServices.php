<?php

namespace App\Services\SatuanInventori;

use App\Models\SatuanInventori;

class SatuanInventoriQueryServices
{
    public function findAll()
    {
        return SatuanInventori::all();
    }

    public function findById(string $id)
    {
        return SatuanInventori::findOrFail($id);
    }
}
