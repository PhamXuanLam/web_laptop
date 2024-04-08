<?php

namespace App\Service;

use App\Models\District;

class DistrictService {
    public function getDistrictById($district_id) {
        return District::query()
                ->select(["*"])
                ->find($district_id);
    }

    public function getNameById($district_id) {
        $province = $this->getDistrictById($district_id);

        return $province ? $province->name : null;
    }
}