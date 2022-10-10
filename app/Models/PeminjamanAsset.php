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
        return $this->hasMany(DetailPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function perpanjangan_peminjaman_asset()
    {
        return $this->hasMany(PerpanjanganPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function request_peminjaman_asset()
    {
        return $this->hasMany(RequestPeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }
}
