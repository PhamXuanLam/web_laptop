<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Service\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function test() 
    {
        $imageUrl = app(ImageService::class)->getImageUrl("", "", asset(Image::DEFAULT));
        return response()->json(['image_url' => $imageUrl]);
    }
}
