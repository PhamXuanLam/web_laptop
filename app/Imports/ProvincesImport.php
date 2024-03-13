<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Province;

class ProvincesImport implements ToModel
{
     /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        {
            $provinceId = $row[1];

            if (!Province::query()->where('id', $provinceId)->exists()) {
                return new Province([
                    'id' => $row[1],
                    'name' => $row[0]
                ]);
            } else {
                return null;
            }
        }
    }
}
