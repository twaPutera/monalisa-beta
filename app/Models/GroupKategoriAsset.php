<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuid;

class GroupKategoriAsset extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    public function kategori_asset()
    {
        return $this->hasMany(KategoriAsset::class, 'id_group_kategori_asset', 'id');
    }
}
