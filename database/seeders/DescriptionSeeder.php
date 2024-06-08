<?php

namespace Database\Seeders;

use App\Models\Description;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::query()->select(["*"])->get();
        foreach($products as $product){
            Description::factory()->create([
                'product_id' => $product->id,
                'guarantee' => fake()->word,
                'mass' => fake()->randomFloat(2, 0, 1000),
                'cpu' => fake()->word,
                'screen' => fake()->randomElement(['LCD', 'LED', 'OLED']),
                'storage' => fake()->randomElement(['HDD', 'SSD', 'NVMe SSD']),
                'graphics' => fake()->word,
                'battery' => fake()->randomFloat(2, 0, 100),
                'ram' => fake()->randomNumber(2),
                'operating_system' => fake()->word,
                'other' => fake()->sentence,
            ]);
        }
    }
}
