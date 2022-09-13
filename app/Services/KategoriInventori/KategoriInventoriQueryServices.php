<?php

namespace App\Services\KategoriInventori;

use App\Models\KategoriInventori;

class KategoriInventoriQueryServices
{
    public function findAll()
    {
        return KategoriInventori::all();
    }

    public function findById(string $id)
    {
        return KategoriInventori::findOrFail($id);
    }
}
