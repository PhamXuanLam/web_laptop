<?php

namespace App\Service;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isEmpty;

class CustomerService {

    protected AddressService $addressService;

    public function __construct($addressService)
    {
        $this->addressService = $addressService;
    }

    public function storeCustomer(int $account_id, Customer $customer, $province_id = null, $district_id = null, $commune_id = null) 
    {
        DB::beginTransaction();
        try {
            $customer->account_id = $account_id;

            if ($province_id && $district_id && $commune_id) {
                $address = $this->addressService->getAddress($province_id, $district_id, $commune_id);

                if($address == null) {
                    $customer->address_id = $this->addressService->storeAddress($province_id, $district_id, $commune_id);
                } else {
                    $customer->address_id = $address->id;
                }
            }

            $customer->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
        }
    }

    public function getCustomerByAccountId($account_id) {
        return Customer::query()
                ->select(["*"])
                ->with(['address' => function($query) {
                    $query->select(['id', 'name', 'province_id', 'district_id', 'commune_id']);
                }])
                ->where("account_id", $account_id)
                ->first();
    }
}