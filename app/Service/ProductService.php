<?php

namespace App\Service;

use App\Models\Product;

class ProductService {
    public function getProductByBrand(string $brand) {
        return Product::query()
            ->select(['*'])
            ->where("brand", 'LIKE', '%' . $brand . '%')
            ->get();
    }
}