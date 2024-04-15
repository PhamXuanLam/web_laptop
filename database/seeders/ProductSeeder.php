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
        $categories = Category::all();
        $faker = \Faker\Factory::create();
        $demandList = ['Graphics', 'Gaming', 'Study', 'Enterprise'];
        foreach($categories as $category)
        {   
            $price = $faker->numberBetween(500, 50000) * 2 * 3;
            
            Product::factory()->create([
                'name' => $faker->name(),
                'price' => $price,
                'quantity' => $faker->numberBetween(1, 100),
                'slug' => $faker->slug(),
                'demand' => Arr::random($demandList), // Sử dụng lớp Arr ở đây
                'status' => $faker->boolean(),
                'category_id' => $category->id,
                'evaluate' => $faker->numberBetween(1, 5),
                'avatar' => time() . '.png',
                'brand' => $faker->company(),
                'size' => $faker->randomElement(['S', 'M', 'L', 'XL']),
                'color' => $faker->safeColorName(),
            ]);
        }   
    }
}
