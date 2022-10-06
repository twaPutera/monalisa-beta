<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory, Uuid;

    public function approvable()
    {
        return $this->morphTo();
    }

    public function linkApproval()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return route('user.asset-data.pemindahan.approve', $this->approvable_id);
        } else if ($this->approvable instanceof PemutihanAsset) {
            return '#';
            // return route('pemutihan_asset.show', $this->approvable->id);
        }
    }
}
