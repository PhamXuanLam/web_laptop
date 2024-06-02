<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Admin;
use App\Models\Employee;
use App\Service\AddressService;
use App\Service\CustomerService;
use App\Service\OrderService;
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

    public function accept(string $order_id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Employee::EMPLOYEE_ROLE) {
                $res = app(OrderService::class)->accept($account->id, $order_id);
                if($res == true) {
                    return response()->json([
                        "status" => true,
                        "message" => "Order has been accepted",
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

    public function getOrderPending()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Employee::EMPLOYEE_ROLE) {
                $response = app(OrderService::class)->getOrderPending();
                return response()->json([
                   $response
                ]);
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

    // public function update()

    public function index()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $orders = app(OrderService::class)->getAll();
                $res = [];
                foreach($orders as $order) {
                    $res[] = [
                        "employee_id" => $order->employee_id,
                        "customer_id" => $order->customer_id,
                        "created_at" => $order->created_at,
                        "total" => $order->total,
                    ];
                }
                return response()->json(
                    $res
                );
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
