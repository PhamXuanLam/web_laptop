<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::query()->get();
        $employees = Employee::query()->get();
        $address = Address::query()->get();
        $orderItems = OrderItems::query()->get();
        foreach($orderItems as $item) {
            $year = rand(2018, 2024);
            $date = Carbon::create($year, rand(1, 12), rand(1, 28), rand(0, 23), rand(0, 59), rand(0, 59));
            $product = Product::query()->find($item->product_id);
            Order::factory()->create([
                'id' => $item->id,
                "customer_id" => $customers->random()->id,
                "employee_id" => $employees->random()->id,
                "address_id" => $address->random()->id,
                "tax" => 0,
                "discount" => 0,
                "status" => 2,
                "total" => $item->quantity * $product->price,
                "pay" => $item->quantity * $product->price,
                "created_at" => $date->format('Y-m-d H:i:s'),
                "updated_at" => $date->format('Y-m-d H:i:s')
            ]);
        }
    }
}
