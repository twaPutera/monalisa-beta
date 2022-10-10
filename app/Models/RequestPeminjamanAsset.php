<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class RequestPeminjamanAsset extends Model
{
    use HasFactory, Uuid;

    public function peminjaman_asset()
    {
        return $this->belongsTo(PeminjamanAsset::class, 'id_peminjaman_asset', 'id');
    }

    public function kategori_asset()
    {
        return $this->belongsTo(KategoriAsset::class, 'id_kategori_asset', 'id');
    }
}
