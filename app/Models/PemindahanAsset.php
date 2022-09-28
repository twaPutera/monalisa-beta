<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class PemindahanAsset extends Model
{
    use HasFactory, Uuid;

    public function detail_pemindahan_asset()
    {
        return $this->hasOne(DetailPemindahanAsset::class, 'id_pemindahan_asset', 'id');
    }

    public function approval_pemindahan_asset()
    {
        return $this->hasMany(ApprovalPemindahanAsset::class, 'id_pemindahan_asset', 'id');
    }
}
