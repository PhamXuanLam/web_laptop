<?php

namespace App\Imports;

use App\Models\District;
use Maatwebsite\Excel\Concerns\ToModel;

class DistrictsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $districtId = $row[3];
        if (!District::query()->where('id', $districtId)->exists()) {
            return new District([
                'id' => $row[3],
                'name' => $row[2],
                'province_id' => $row[1],
            ]);
        } else {
            return null;
        }
    }
}


