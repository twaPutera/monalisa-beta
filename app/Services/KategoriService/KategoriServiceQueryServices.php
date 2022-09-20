<?php

namespace App\Services\KategoriService;

use App\Models\KategoriService;

class KategoriServiceQueryServices
{
    public function findAll()
    {
        return KategoriService::all();
    }

    public function findById(string $id)
    {
        return KategoriService::findOrFail($id);
    }
}
