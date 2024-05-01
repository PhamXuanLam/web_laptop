<?php

namespace App\Http\Controllers;

use App\Service\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function brand(string $brand) {
        $products = app(ProductService::class)->getProductByBrand($brand);

        return response()->json([
            "messages" => "List product by brand", 
            "products" => $products
        ]);
    }
        
    public function demands(){
        $demands = app(ProductService::class)->getDemands();
        return response()->json([
            "messages" => "List demand",
            "Demands" => $demands
        ]);
    }
    
}
