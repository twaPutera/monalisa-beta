<?php

namespace App\Helpers;


class FileHelpers
{
    public static function saveFile($file, $path, $filename)
    {
        $file->move($path, $filename);
        return $filename;
    }
}
