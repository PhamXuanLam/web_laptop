<?php

namespace App\Http\Controllers;

use App\Service\AccountService;
use App\Http\Requests\CustomerRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Models\Customer;
use App\Service\AddressService;
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

    public function index()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $customers = app(CustomerService::class)->getAll();
                $response = [];
                foreach($customers as $customer) {
                    $account = app(AccountService::class)->getAccountByAccountId($customer->account_id);
                    $address = app(AddressService::class)->getAddress("", "", "", $customer->address_id);
                    $response[] = [
                        'id' => $customer->id,
                        'first_name' => $account->first_name,
                        'last_name' => $account->last_name,
                        'birth_day' => $account->birth_day,
                        'email' => $account->email,
                        'address' => $address->name,
                    ];
                }
                return response()->json($response);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "You do not have access!",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }

    public function delete(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(CustomerService::class)->delete($id);
                if($res == true) {
                    return response()->json([
                        "status" => true,
                        "message" => "Delete successfully!",
                    ]);
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "You do not have access!",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }

    public function search(string $keyword)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $accounts = app(AccountService::class)->getCustomerByKeyword($keyword);
                $response = [];
                foreach($accounts as $account) {
                    $customer = app(CustomerService::class)->getCustomerByAccountId($account->id);
                    $response[] = [
                        'id' => $customer->id,
                        'first_name' => $account->first_name,
                        'last_name' => $account->last_name,
                        'birth_day' => $account->birth_day,
                        'email' => $account->email,
                        'address' => $customer->address->name,
                    ];
                }
                return response()->json($response);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "You do not have access!",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }

    public function filter(string $filter, string $order)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                if ($filter == "buy") {
                    $res = app(CustomerService::class)->getCustomerByPurchases($order);
                } else {
                    $res = app(CustomerService::class)->getCustomerByReviews($order);
                }
                return response()->json([$res]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "You do not have access!",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }
}
