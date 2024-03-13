<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roleList = ["ADMIN", "CUSTOMER", "EMPLOYEE"];

        return [
            "username" => fake()->username(),
            "password" => Hash::make('password'),
            'remember_token' => Str::random(10),
            'birth_day' => fake()->date(),
            'last_name' => fake()->firstName(),
            'first_name' => fake()->lastName(),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'avatar' => time() . '.png',
            "status" => random_int(0, 1),
            "role" => Arr::random($roleList),
        ];
    }
}
