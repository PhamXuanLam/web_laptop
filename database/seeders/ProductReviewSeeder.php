<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\ProductReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::query()->get();

        foreach($customers as $customer) {
            ProductReview::factory()->create([
                "customer_id" => $customer->id,
                "product_id" => random_int(1, 120),
                "rate" => random_int(1, 5),
                "comment" => Str::random(10),
            ]);
        }
    }
}
