<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list_account_id = Account::query()->select(['id'])->get();

        foreach($list_account_id as $account_id) {
            Status::factory()->create([
                "status" => Status::STATUS_ACTIVE,
                "account_id" => $account_id,
                "time_in" => now(),
                'time_out' => now()->addMinutes(15)
            ]);
        }
    }
}
