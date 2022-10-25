<?php

namespace App\Helpers;

use App\Models\AssetData;
use App\Models\DepresiasiAsset;

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

    public static function depresiasiAsset(AssetData $asset, $tanggal_depresiasi)
    {
        $nilai_buku_akhir = $asset->nilai_buku_asset - $asset->nilai_depresiasi;

        $depresiasi = new DepresiasiAsset();
        $depresiasi->id_asset_data = $asset->id;
        $depresiasi->nilai_depresiasi = $asset->nilai_depresiasi;
        $depresiasi->tanggal_depresiasi = $tanggal_depresiasi;
        $depresiasi->nilai_buku_awal = $asset->nilai_buku_asset;
        $depresiasi->nilai_buku_akhir = $nilai_buku_akhir < 1 ? 1 : $nilai_buku_akhir;
        $depresiasi->save();

        $asset->nilai_buku_asset = $nilai_buku_akhir < 1 ? 1 : $nilai_buku_akhir;
        $asset->save();

        return [$depresiasi->id, $asset->id, $asset->nilai_buku_asset, $nilai_buku_akhir];
    }

    public static function getAwalTanggalDepresiasi($date)
    {
        if (date('d', strtotime($date)) > 15) {
            $date = date('Y-m-d', strtotime($date . ' +1 month'));
        }
        return date('Y-m-15', strtotime($date));
    }

    public static function getAkhirtanggalDepresiasi($date, $tahun)
    {
        $date = date('Y-m-15', strtotime($date . $tahun . ' year'));
        return $date;
    }

    public static function getDiffOfMonth($date1, $date2)
    {
        $date1 = new \DateTime($date1);
        $date2 = new \DateTime($date2);
        $diff = $date1->diff($date2);
        return $diff->m + ($diff->y * 12);
    }

    public static function generateUmurAsset($date, $umur)
    {
        $diff = self::getDiffOfMonth($date, date('Y-m-d'));
        $umur_asset = $umur - $diff;

        return ($umur_asset/12);
    }
}
