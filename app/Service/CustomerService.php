<?php

namespace App\Service;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService {

    public function storeCustomer(int $account_id, Customer $customer) 
    {
        DB::beginTransaction();
        try {
            $customer->account_id = $account_id;
            $customer->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
        }
    }
}