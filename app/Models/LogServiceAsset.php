<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogServiceAsset extends Model
{
    use HasFactory, Uuid;

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id');
    }
}
