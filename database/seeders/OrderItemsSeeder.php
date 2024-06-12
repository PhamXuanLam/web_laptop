<?php

namespace Database\Seeders;

use App\Models\OrderItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 1000; $i++) {
            OrderItems::factory()->create([
                'id' => $i,
                "order_id" => $i,
                "product_id" => random_int(1, 120),
                "quantity" => random_int(1, 10)
            ]);
        }
    }
}
