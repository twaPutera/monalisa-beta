<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\UserSso\UserSsoQueryServices;
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

    public function approvalType()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return "Pemindahan Asset";
        } else if ($this->approvable instanceof PemutihanAsset) {
            return "Pemutihan Asset";
        } else if ($this->approvable instanceof PeminjamanAsset) {
            return "Peminjaman Asset";
        }

        return "Tipe Tidak Terdaftar";
    }

    public function getPembuatApproval()
    {
        $userSso = new UserSsoQueryServices();
        $guid = $this->approvable->created_by;
        $name = 'Tidak Terdaftar di Siska';
        if (isset($guid)) {
            $user_sso = $userSso->getUserByGuid($guid);
            $name = isset($user_sso[0]) ? $user_sso[0]['nama'] : 'User Not Found';
        }

        return $name;
    }
}
