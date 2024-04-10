<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = fake()->numberBetween(500, 50000) * 2 * 3;

        return [
            'name' =>fake()->name(),
            'price' => $price,
            'quantity' => fake()->numberBetween(1, 100),
            'slug' => fake()->slug(),
            'status' => fake()->randomElement(['available', 'unavailable']),
            'category_id' => Category::inRandomOrder()->first()->id,
            'evaluate' => fake()->numberBetween(1, 5),
            'avatar' => time() . '.png',
            'brand' => fake()->company(),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL']),
            'color' => fake()->safeColorName(),
        ];
    }
}
