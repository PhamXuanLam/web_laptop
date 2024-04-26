<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Service\AddressService;
use App\Service\CustomerService;
use App\Service\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function order(OrderRequest $request) 
    {
        $params = $request->only([
            'province_id',
            'district_id',
            'commune_id',
        ]);

        $account = Auth::guard('account_api')->user();
        if($account) {
            $customer = app(CustomerService::class)->getCustomerByAccountId($account->id);

            if($customer->address == null) {
                app(CustomerService::class)->storeCustomer($account->id, $customer, 
                $params["province_id"], $params["district_id"], $params["commune_id"]);
                $account->address = app(AddressService::class)->getAddress($params["province_id"], $params["district_id"], $params["commune_id"]);
            } else {
                $account->address = $customer->address;
            }

            $response = app(OrderService::class)->order($customer->id);
            
            return response()->json($response);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }
}
