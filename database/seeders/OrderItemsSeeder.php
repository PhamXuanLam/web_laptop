<?php

namespace Database\Seeders;

use App\Models\OrderItems;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for($i = 1; $i <= 1000; $i++) {
            $year = rand(2018, 2024);
            $date = Carbon::create($year, rand(1, 12), rand(1, 28), rand(0, 23), rand(0, 59), rand(0, 59));
            OrderItems::factory()->create([
                'id' => $i,
                "order_id" => $i,
                "product_id" => random_int(1, 120),
                "quantity" => random_int(1, 10),
                "created_at" => $date->format('Y-m-d H:i:s'),
                "updated_at" => $date->format('Y-m-d H:i:s')
            ]);
        }
    }
}
