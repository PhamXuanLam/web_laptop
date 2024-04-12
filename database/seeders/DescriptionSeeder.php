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
                'Guarantee' => fake()->word,
                'mass' => fake()->randomFloat(2, 0, 1000),
                'CPU' => fake()->word,
                'screen' => fake()->randomElement(['LCD', 'LED', 'OLED']),
                'Storage' => fake()->randomElement(['HDD', 'SSD', 'NVMe SSD']),
                'Graphics' => fake()->word,
                'battery' => fake()->randomFloat(2, 0, 100),
                'RAM' => fake()->randomNumber(4),
                'Operating_system' => fake()->word,
                'other' => fake()->sentence,
            ]);
        }
    }
}
