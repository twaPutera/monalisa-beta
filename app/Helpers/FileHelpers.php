<?php

namespace App\Helpers;

use File;

class FileHelpers
{
    public static function saveFile($file, $path, $filename)
    {
        $file->move($path, $filename);
        return $filename;
    }

    public static function deleteFile($path)
    {
        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    File::delete($path);

                    return true;
                }
            }
        }

        return false;
    }

    public static function viewFile($path, $filename)
    {
        $extesion = explode('.', $filename);

        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    if (count($extesion) > 1) {
                        if (last($extesion) == 'pdf' || last($extesion) == 'png' || last($extesion) == 'jpg' || last($extesion) == 'jpeg') {
                            return response()->file($path);
                        }
                        return response()->download(
                            $path,
                            \Str::slug($filename) . '.' . last($extesion)
                        );
                    }
                    return response()->download(
                        $path
                    );
                }
            }
        }

        return response('FILE_NOT_FOUND', 404);
    }

    public static function removeFile($path)
    {
        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    File::delete($path);

                    return true;
                }
            }
        }

        return false;
    }
}
