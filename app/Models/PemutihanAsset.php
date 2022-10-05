<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemutihanAsset extends Model
{
    use HasFactory, Uuid;

    public function detail_pemutihan_asset()
    {
        return $this->hasMany(DetailPemutihanAsset::class, 'id_pemutihan_asset', 'id');
    }

    public function approval_pemutihan_asset()
    {
        return $this->hasMany(ApprovalPemutihanAsset::class, 'id_pemutihan_asset', 'id');
    }
}
