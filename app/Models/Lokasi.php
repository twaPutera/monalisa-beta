<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lokasi extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public function asset_data()
    {
        return $this->hasMany(AssetData::class, 'id_lokasi', 'id');
    }

    public function detail_inventori_data()
    {
        return $this->hasMany(DetailInventoriData::class, 'id_lokasi', 'id');
    }
}
