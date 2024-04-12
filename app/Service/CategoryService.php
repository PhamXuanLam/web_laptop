<?php

namespace App\Service;

use App\Models\Category;

class CategoryService {
    
    public function getCategories() {
        return Category::query()
            ->select(["*"])
            ->get();
    }

    public function getCategory(int $category_id) {
        return Category::query()
            ->select(["*"])
            ->with(['products'])
            ->find($category_id);
    }

    public function getBrandsByCategoryId(int $category_id) {
        return Category::query()
            ->select(["*"])
            ->with(['products'])
            ->find($category_id)
            ->products()
            ->distinct('brand')
            ->pluck('brand');
    }
}