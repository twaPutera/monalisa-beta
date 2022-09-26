<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class LogAsset extends Model
{
    use HasFactory, Uuid;

    public function asset()
    {
        return $this->belongsTo(AssetData::class, 'asset_id');
    }
}
