<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPenambahanInventori extends Model
{
    use HasFactory, Uuid;
    protected $table = 'log_penambahan_inventori';
    public function inventori_data()
    {
        return $this->belongsTo(InventoriData::class, 'id_inventori', 'id');
    }
}
