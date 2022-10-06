<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class PeminjamanAsset extends Model
{
    use HasFactory, Uuid;

    public function approval()
    {
        return $this->morphOne(Approval::class, 'approvable');
    }

    public function detail_peminjaman_asset()
    {
        return $this->hasOne(DetailPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }
}
