<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use App\Services\UserSso\UserSsoQueryServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\User\UserQueryServices;

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
        } elseif ($this->approvable instanceof PemutihanAsset) {
            return route('admin.pemutihan-asset.show', $this->approvable_id);
        } elseif ($this->approvable instanceof PeminjamanAsset) {
            return route('admin.peminjaman.show', $this->approvable_id);
        }
    }

    public function linkUpdateApproval()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return route('admin.approval.pemindahan.change-status', $this->approvable_id);
        } elseif ($this->approvable instanceof PemutihanAsset) {
            return route('admin.approval.pemutihan.change-status', $this->approvable_id);
        } elseif ($this->approvable instanceof PeminjamanAsset) {
            return route('admin.approval.peminjaman.change-status', $this->approvable_id);
        }
    }

    public function approvalType()
    {
        if ($this->approvable instanceof PemindahanAsset) {
            return 'Pemindahan Asset';
        } elseif ($this->approvable instanceof PemutihanAsset) {
            return 'Pemutihan Asset';
        } elseif ($this->approvable instanceof PeminjamanAsset) {
            return 'Peminjaman Asset';
        }

        return 'Tipe Tidak Terdaftar';
    }

    public function getPembuatApproval()
    {
        $userSso = new UserSsoQueryServices();
        $userService = new UserQueryServices();
        $guid = $this->approvable->created_by;
        $name = 'Tidak Terdaftar di Siska';
        if (isset($guid)) {
            if (config('app.sso_siska')) {
                $user = $this->created_by == null ? null : $userSso->getUserByGuid($this->created_by);
                $name = isset($user[0]) ? $user[0]['nama'] : 'Not Found';
            } else {
                $user = $this->created_by == null ? null : $userService->findById($this->created_by);
                $name = isset($user) ? $user->name : 'Not Found';
            }
        }

        return $name;
    }
}
