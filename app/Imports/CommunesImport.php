<?php

namespace App\Imports;

use App\Models\Commune;
use Maatwebsite\Excel\Concerns\ToModel;

class CommunesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if ($row[5] == null)
            return null;
        return new Commune([
            'id' => $row[5],
            'name' => $row[4],
            'district_id' => $row[3]
        ]);
    }
}
