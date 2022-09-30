<?php

namespace App\Services\PemindahanAsset;

use App\Models\PemindahanAsset;
use App\Models\DetailPemindahanAsset;
use App\Models\AssetData;
use App\Models\ApprovalPemindahanAsset;

class PemindahanAssetQueryServices
{
    public function findById(string $id)
    {
        $pemindahan_asset = PemindahanAsset::query()
            ->with(['detail_pemindahan_asset', 'approval_pemindahan_asset'])
            ->where('id', $id)
            ->first();

        return $pemindahan_asset;
    }
}
