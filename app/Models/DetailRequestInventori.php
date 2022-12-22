<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailRequestInventori extends Model
{
    use HasFactory, Uuid;
    public function request_inventori()
    {
        return $this->belongsTo(RequestInventori::class, 'request_inventori_id', 'id');
    }

    public function inventori()
    {
        return $this->belongsTo(InventoriData::class, 'inventori_id', 'id');
    }
}
