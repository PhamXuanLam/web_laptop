<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountService {

    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    public function storeAccount($params, Account $account) 
    {
        DB::beginTransaction();
        try {

            if(isset($params['password']) && $params['password']) {
                $account->password = Hash::make($params['password']);
            }

            if(isset($params['role']) && $params['role']) {
                $account->role = $params['role'];

            } else {
                $account->role = Customer::CUSTOMER_ROLE;
            }

            $account->fill($params);

            $account->save();
            
            if($account->role === Customer::CUSTOMER_ROLE) {
                $this->customerService->storeCustomer($account->id,new Customer());
            }
            DB::commit();
            return [
                "account" => $account, 
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return ['error' => 'cap nhat that bai'];
        }
    }
}