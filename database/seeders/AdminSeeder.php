<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::query()
            ->where('role', 'ADMiN')
            ->select(['*'])
            ->get();

        while(!$accounts->isEmpty()) {
            $account = $accounts->shift();
            Admin::factory()->create([
                'account_id' => $account->id,
            ]);
        }
    }
}
