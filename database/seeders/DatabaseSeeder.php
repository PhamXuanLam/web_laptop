<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        app(AccountSeeder::class)->run();
        app(AddressSeeder::class)->run();
        app(AdminSeeder::class)->run();
        app(CustomerSeeder::class)->run();
        app(EmployeeSeeder::class)->run();
        app(CategorySeeder::class)->run();
        app(SupplierSeeder::class)->run();
        app(ProductSeeder::class)->run();
        app(ImageSeeder::class)->run();
        app(DescriptionSeeder::class)->run();
    }
}
