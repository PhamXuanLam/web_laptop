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
    
   public function getDemands(){
    $demands = Product::query()
                ->select('demand')
                ->distinct()
                ->get()
                ->pluck('demand'); // Lấy ra giá trị của cột 'demand' thành một mảng

    return $demands->toArray(); // Chuyển đổi collection thành mảng
}

}