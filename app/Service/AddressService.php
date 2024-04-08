<?php

namespace App\Service;

use App\Models\Address;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddressService {
    protected ProvinceService $provinceService;
    protected DistrictService $districtService;
    protected CommuneService $communeService;

    // public function __construct($provinceService, $districtService, $communeService)
    // {
    //     $this->provinceService = $provinceService;
    //     $this->districtService = $districtService;
    //     $this->communeService = $communeService;   
    // }

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
                $this->provinceService->getNameById($province_id) . " - " .
                $this->districtService->getNameById($district_id) . " - " .
                $this->communeService->getNameById($commune_id);
            $address->save();
            DB::commit();
            return $address->id;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
        }
    }
}