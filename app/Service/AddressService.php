<?php

namespace App\Service;

use App\Models\Address;
use App\Models\Commune;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressService {

    public function getAddress($province_id, $district_id, $commune_id) {
        return Address::query()
                ->select(["*"])
                ->where("province_id", $province_id)
                ->where("district_id", $district_id)
                ->where("commune_id", $commune_id)
                ->first();
    }

    public function storeAddress($province_id, $district_id, $commune_id) {
        DB::beginTransaction();
        try {
            $address = new Address();
            $address->province_id = $province_id;
            $address->district_id = $district_id;
            $address->commune_id = $commune_id;
            $address->name = 
                $this->getProvinceNameById($province_id) . " - " .
                $this->getDistrictNameById($district_id) . " - " .
                $this->getCommuneNameById($commune_id);
            $address->save();
            DB::commit();
            return $address->id;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
        }
    }

    public function getProvinceById($province_id) {
        return Province::query()
                ->select(["*"])
                ->find($province_id);
    }

    public function getProvinceNameById($province_id) {
        $province = $this->getProvinceById($province_id);

        return $province ? $province->name : null;
    }

    public function getDistrictById($district_id) {
        return District::query()
                ->select(["*"])
                ->find($district_id);
    }

    public function getDistrictNameById($district_id) {
        $province = $this->getDistrictById($district_id);

        return $province ? $province->name : null;
    }

    public function getCommuneById($commune_id) {
        return Commune::query()
                ->select(["*"])
                ->find($commune_id);
    }

    public function getCommuneNameById($commune_id) {
        $province = $this->getCommuneById($commune_id);

        return $province ? $province->name : null;
    }
}