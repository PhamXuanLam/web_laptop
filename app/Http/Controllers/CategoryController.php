<?php

namespace App\Http\Controllers;

use App\Service\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = app(CategoryService::class)->getCategories();

        return response()->json(["categories" => $categories]);
    }

    public function show(int $category_id) {
        $category = app(CategoryService::class)->getCategory($category_id);

        return response()->json([
            "data" => $category
        ]);
    }

    public function brands(int $category_id) {
        $brands = app(CategoryService::class)->getBrandsByCategoryId($category_id);

        return response()->json([
            "brands" => $brands
        ]);
    }
}
