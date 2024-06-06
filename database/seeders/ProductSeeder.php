<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Arr; // Nhập lớp Arr
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductSeeder extends Seeder
{
    /**
     * Chạy các seed vào cơ sở dữ liệu.
     */
    public function run(): void

    {
        $categories = Category::query()->select(["*"])->get();
        $brands = ['Dell', 'HP', 'Lenovo', 'Apple', 'Asus', 'MSI', 'Acer', 'Samsung', 'Microsoft', 'Razer'];
        $demandList = ['Graphics', 'Gaming', 'Study', 'Enterprise'];
        for($i = 0; $i < 30; $i++) {
            foreach($categories as $category)
            {
                $price = fake()->numberBetween(500, 50000) * 2 * 3;

                Product::factory()->create([

                    'name' =>fake()->name(),
                    'price' => $price,
                    'quantity' => fake()->numberBetween(1, 100),
                    'slug' => fake()->slug(),
                    'status' => random_int(0,1),
                    'category_id' => $category->id,
                    'brand' => Arr::random($brands),
                    'size' => fake()->randomElement(['S', 'M', 'L', 'XL']),
                    'color' => fake()->safeColorName(),
                    'demand' => Arr::random($demandList),
                ]);
            }
        }
    }
}
