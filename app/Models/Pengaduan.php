<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    use HasFactory, Uuid;

    public function image()
    {
        return $this->morphMany(AssetImage::class, 'imageable');
    }

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset_data', 'id');
    }
}
