<?php

namespace App\Http\Controllers;

use App\Service\AccountService;
use App\Http\Requests\Customer\RegisterRequest;
use App\Models\Account;
use App\Models\Customer;
use App\Service\CustomerService;
use Illuminate\Support\Facades\Auth;


class CustomerController extends Controller
{
    protected AccountService $accountService;
    // protected CustomerService $customerService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
        // $this->customerService = $customerService;
    }

    public function register(RegisterRequest $request) 
    {
        $params = $request->only([
            'username', 'email', 
            'first_name', 'last_name',
            'phone', 'password', 
        ]);
        
        $response = $this->accountService->storeAccount($params, new Account());
        
        return response()->json($response);
    }

    public function show() 
    {
        dd(1);
        $account = Auth::guard('account_api')->user();
        if($account) 
            $customer = Customer::query()
                ->select(["*"])
                ->with(['address' => function($query) {
                    $query->select(['id', 'name', 'province_id', 'district_id', 'commune_id']);
                }])
                ->where("account_id", $account->id)
                ->first();
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
