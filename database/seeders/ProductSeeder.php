<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->select(["*"])->get();
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
                'evaluate' => fake()->numberBetween(1, 5),
                'avatar' => time() . '.png',
                'brand' => fake()->company(),
                'size' => fake()->randomElement(['S', 'M', 'L', 'XL']),
                'color' => fake()->safeColorName(),
            ]);
        }   
        
    }
}
