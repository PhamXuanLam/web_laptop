<?php

namespace App\Service;

use Illuminate\Support\Facades\Storage;

class ImageService {
    public static function uploadImage($id, $path, $file, $oldFile = null)
    {
        $fileName = time() . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        if (!empty($oldFile)) {
            Storage::delete($path . $id . '/' . $oldFile);
        }
        $newFilePath = $path . $id . "/" . $fileName;
        $directory = dirname($newFilePath);

        if (!Storage::exists($directory)) {
            Storage::makeDirectory($directory);
        }
        Storage::put($newFilePath, file_get_contents($file));

        return $fileName;
    }

    public static function getImageUrl($path, $fileName, $default)
    {
        $imgDir = $path . $fileName;
        if (!Storage::exists($imgDir)) {
            return $default;
        }

        return Storage::url($imgDir);
    }

}