<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Address;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::query()
            ->where('role', 'EMPLOYEE')
            ->select(['*'])
            ->get();

        $address = Address::query()
            ->select(['id'])
            ->get();

        while(!$accounts->isEmpty()) {
            $account = $accounts->shift();
            Employee::factory()->create([
                'account_id' => $account->id,
                'address_id' => $address->random(),
                'salary' => 8000000,
            ]);
        }
    }
}
