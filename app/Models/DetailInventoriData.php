<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailInventoriData extends Model
{
    use HasFactory, Uuid;

    public function inventori_data()
    {
        return $this->belongsTo(InventoriData::class, 'id_inventori', 'id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id');
    }
}
