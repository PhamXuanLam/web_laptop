<?php

namespace Database\Seeders;

use App\Jobs\ImportAdministrative;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commune;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        dispatch(new ImportAdministrative());
        
        $communes = Commune::query()
            ->with(['district', 'district.province'])
            ->select(['*'])
            ->get();   

        for ($i = 0; $i < 30; $i++) {
            $commune = $communes->random();
            $district = $commune->district;
            $province = $district->province;

            Address::factory()->create([
                'commune_id' => $commune->id,
                'district_id' => $district->id,
                'province_id' => $province->id,
                'name' => $province->name . ' - ' . $district->name . ' - ' . $commune->name,
            ]);
        }
    }
}
