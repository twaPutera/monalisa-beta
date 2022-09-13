<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SatuanInventori extends Model
{
    use HasFactory, Uuid, SoftDeletes;
    protected $table = "satuan_inventories";
}
