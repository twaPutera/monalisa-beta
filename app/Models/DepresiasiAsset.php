<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class DepresiasiAsset extends Model
{
    use HasFactory, Uuid;

    public function asset_data()
    {
        return $this->belongsTo(AssetData::class, 'id_asset_data');
    }
}
