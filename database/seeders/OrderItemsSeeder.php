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
        for($i = 1; $i <= 10000; $i++) {
            $year = rand(2018, 2024);
            if ($year == 2024) {
                $currentDate = Carbon::now();
                $month = $currentDate->month;
                $day = $currentDate->day;
                $hour = $currentDate->hour;
                $minute = $currentDate->minute;
                $second = $currentDate->second;
            } else {
                // Nếu không phải năm 2024, lấy ngày ngẫu nhiên
                $month = rand(1, 12);
                $day = rand(1, 28);
                $hour = rand(0, 23);
                $minute = rand(0, 59);
                $second = rand(0, 59);
            }
            $date = Carbon::create($year, $month, $day, $hour, $minute, $second);
            OrderItems::factory()->create([
                "order_id" => $i,
                "product_id" => random_int(1, 120),
                "quantity" => random_int(1, 10),
                "created_at" => $date->format('Y-m-d H:i:s'),
                "updated_at" => $date->format('Y-m-d H:i:s')
            ]);
        }
    }
}
