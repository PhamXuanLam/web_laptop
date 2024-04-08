<?php

namespace App\Service;

use App\Models\Commune;

class CommuneService {
    public function getCommuneById($commune_id) {
        return Commune::query()
                ->select(["*"])
                ->find($commune_id);
    }

    public function getNameById($commune_id) {
        $province = $this->getCommuneById($commune_id);

        return $province ? $province->name : null;
    }
}