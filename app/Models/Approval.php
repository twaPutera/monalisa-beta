<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Approval extends Model
{
    use HasFactory, Uuid;

    public function approvable()
    {
        return $this->morphTo();
    }
}
