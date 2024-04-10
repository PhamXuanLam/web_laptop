<?php

namespace App\Http\Controllers;

use App\Service\AccountService;
use App\Http\Requests\CustomerRequest;
use App\Models\Account;
use App\Service\CustomerService;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    public function register(CustomerRequest $request) 
    {
        $params = $request->only([
            'username', 'email', 
            'first_name', 'last_name',
            'phone', 'password', 
        ]);
        
        $account = app(AccountService::class)->storeAccount($params, new Account());
        $account->customer = app(CustomerService::class)->getCustomerByAccountId($account->id);

        return response()->json([
            "status" => true,
            "message" => "Register successfully!",
            "data" => $account,
        ]);
    }

    public function update(CustomerRequest $request) 
    {
        $params = $request->only([
            'username', 'email', 
            'first_name', 'last_name',
            'phone','birth_day', 'avatar', 'role',
            'province_id', 'district_id', 'commune_id'
        ]);

        $account = Auth::guard('account_api')->user();

        $account = app(AccountService::class)->storeAccount($params, $account);

        $customer = app(CustomerService::class)->getCustomerByAccountId($account->id);
        $account->address = $customer->address ? $customer->address : null;

        return response()->json([
            "status" => true,
            "message" => "Update successfully!",
            "data" => $account,
        ]);
    }

    public function show() 
    {
        $account = Auth::guard('account_api')->user();
        if($account) 
            $customer = app(CustomerService::class)->getCustomerByAccountId($account->id);
            $account->address = $customer->address ? $customer->address : null;
        
            return response()->json([
                "status" => true,
                "message" => "Profile information",
                "data" => $account,
            ]);

        return response()->json([
            "status" => false,
            "message" => "Please login!",
        ]);
    }
}
