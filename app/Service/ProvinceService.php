<?php

namespace App\Service;

use App\Models\Province;

class ProvinceService {
    public function getProvinceById($province_id) {
        return Province::query()
                ->select(["*"])
                ->find($province_id);
    }

    public function getNameById($province_id) {
        $province = $this->getProvinceById($province_id);

        return $province ? $province->name : null;
    }
}