<?php

namespace App\Services\Vendor;

use App\Models\Vendor;

class VendorQueryServices
{
    public function findAll()
    {
        return Vendor::all();
    }

    public function findById($id)
    {
        return Vendor::findOrFail($id);
    }

    public function findByKode($kode)
    {
        return Vendor::where('kode_vendor', $kode)->first();
    }
}
