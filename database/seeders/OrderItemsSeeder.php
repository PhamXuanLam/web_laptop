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
                $startOfYear = Carbon::create($year, 1, 1, 0, 0, 0);
                $endOfYear = $currentDate;

                // Tạo ngày ngẫu nhiên từ đầu năm đến thời điểm hiện tại
                $randomTimestamp = rand($startOfYear->timestamp, $endOfYear->timestamp);
                $date = Carbon::createFromTimestamp($randomTimestamp);
            } else {
                // Nếu không phải năm 2024, lấy ngày ngẫu nhiên
                $month = rand(1, 12);
                $day = rand(1, Carbon::create($year, $month)->daysInMonth); // Chọn ngày hợp lệ trong tháng
                $hour = rand(0, 23);
                $minute = rand(0, 59);
                $second = rand(0, 59);
                $date = Carbon::create($year, $month, $day, $hour, $minute, $second);
            }
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
