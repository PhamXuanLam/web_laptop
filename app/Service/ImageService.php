<?php

namespace App\Service;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService {
    public static function uploadImage($id, $path, $file, $oldFile = null)
    {
        $fileName = time() . Str::random(4) . "." . pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
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

    public function store($id, $path, $images)
    {
        $this->delete($id);
        DB::beginTransaction();
        try {
            foreach($images as $file) {
                $image = new Image();
                $image->name = $this->uploadImage($id, $path, $file);
                $image->product_id = $id;
                $image->save();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }
    }

    public function getImageByProductId($product_id)
    {
        return Image::query()
            ->select(["id", "product_id", "name"])
            ->where("product_id", $product_id)
            ->get();
    }

    public function getAvatar($product_id)
    {
        return Image::query()
        ->select(["name"])
        ->where("product_id", $product_id)
        ->first();
    }

    public function delete($product_id)
    {
        DB::beginTransaction();
        try {
            $images = $this->getImageByProductId($product_id);
            foreach($images as $image) {
                Storage::delete(Product::DIRECTORY_IMAGE . $product_id . '/' . $image->name);
                $image->delete();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }
    }
}
