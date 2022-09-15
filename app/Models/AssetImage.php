<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class AssetImage extends Model
{
    use HasFactory, Uuid;

    public function imageable()
    {
        return $this->morphTo();
    }
}
