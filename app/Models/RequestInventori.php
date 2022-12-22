<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestInventori extends Model
{
    use HasFactory, Uuid;

    public function detail_request_inventori()
    {
        return $this->hasMany(DetailRequestInventori::class, 'request_inventori_id', 'id');
    }

    public function log_request_inventori()
    {
        return $this->hasMany(LogRequestInventori::class, 'request_inventori_id', 'id');
    }
}
