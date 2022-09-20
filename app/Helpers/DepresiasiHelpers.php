<?php

namespace App\Helpers;

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
}
