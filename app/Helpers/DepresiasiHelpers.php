<?php

namespace App\Helpers;

use App\Models\AssetData;

class DepresiasiHelpers
{
    public static function getNilaiDepresiasi($nilai_perolehan, $lama_depresiasi)
    {
        $nilai_depresiasi = 0;
        if ($lama_depresiasi > 0) {
            $nilai_depresiasi = $nilai_perolehan / $lama_depresiasi;
        }
        return $nilai_depresiasi;
    }

    public static function getDataAssetDepresiasi()
    {
        $asset = AssetData::query()
            ->select([
                'id',
                'nilai_buku_asset',
                'nilai_depresiasi',
                'nilai_perolehan'
            ])
            ->where('is_pemutihan', '0')
            ->where('is_inventaris', '0')
            ->where('nilai_buku_asset', '>', 1)
            ->get();
        return $asset;
    }
}
