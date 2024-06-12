<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Order;
use App\Service\AccountService;
use App\Service\AddressService;
use App\Service\CustomerService;
use App\Service\EmployeeService;
use App\Service\OrderService;
use App\Service\ProductService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            $address = app(AddressService::class)->getAddress(
                $params['province_id'],
                $params['district_id'],
                $params['commune_id'],
            );
            if($address) {
                $address_id = $address->id;
            } else {
                $address_id = app(AddressService::class)->storeAddress(
                    $params['province_id'],
                    $params['district_id'],
                    $params['commune_id'],
                );
            }

            $response = app(OrderService::class)->order($customer->id, $address_id);
            $subject = 'Đặt hàng thành công';
            $message = 'Đặt hàng thành công, đang đợi người bán phê duyệt';
            $toEmail = $account->email;
            Mail::raw($message, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                        ->subject($subject);
            });

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
                    $order = app(OrderService::class)->getOrderById($order_id);
                    $toEmail = app(AccountService::class)->getAccountByAccountId($order->customer->account_id)->email;
                    $subject = 'Đặt hàng thành công';
                    $message = 'Đặt hàng thành công, đơn hàng của bạn đã được người bán phê duyệt';
                    Mail::raw($message, function ($message) use ($toEmail, $subject) {
                        $message->to($toEmail)
                                ->subject($subject);
                    });
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
                    $orderItems = [];
                    foreach($order->orderItems as $item){
                        $orderItems[] = [
                            'product' => $item->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->product->price
                        ];
                    }
                    $res[] = [
                        "id" => $order->id,
                        "employee" => app(EmployeeService::class)->getNameById($order->employee_id),
                        "customer" => app(CustomerService::class)->getNameById($order->customer_id),
                        "created_at" => $order->created_at,
                        "total" => $order->total,
                        "status" => $order->getStatusLabel(),
                        "order_items" => $orderItems
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

    public function update(OrderRequest $request, string $id)
    {
        $address = $request->only([
            'province_id',
            'district_id',
            'commune_id',
        ]);
        $tmp = app(AddressService::class)->getAddress(
            $address['province_id'],
            $address['district_id'],
            $address['commune_id'],
        );
        if($tmp) {
            $address_id = $tmp->id;
        } else {
            $address_id = app(AddressService::class)->storeAddress(
                $address['province_id'],
                $address['district_id'],
                $address['commune_id'],
            );
        }
        $params = $request->only([
            'customer_id', 'employee_id','total', 'tax','discount','pay','status'
        ]);
        $params['address_id'] = $address_id;

        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $order = app(OrderService::class)->getOrderById($id);
                $res = app(OrderService::class)->store($order, $params);
                return response()->json([
                    $res
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

    public function show(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $order = app(OrderService::class)->getOrderById($id);
                $orderItems = [];
                foreach($order->orderItems as $item){
                    $orderItems[] = [
                        'product' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price
                    ];
                }
                $res = [
                    "id" => $order->id,
                    "employee" => app(EmployeeService::class)->getNameById($order->employee_id),
                    "customer" => app(CustomerService::class)->getNameById($order->customer_id),
                    "total" => $order->total,
                    "status" => $order->getStatusLabel(),
                    'tax' => $order->tax,
                    'discount' => $order->discount,
                    'pay'=> $order->pay,
                    "created_at" => $order->created_at,
                    "update_at" => $order->updated_at,
                    'address' => app(AddressService::class)->getAddress("", "", "", $order->address_id)->name,
                    "order_items" => $orderItems
                ];
                return response()->json($res);
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
                $res = app(OrderService::class)->delete($id);
                return response()->json([
                    $res
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

    public function search(string $keyword)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $orders = app(OrderService::class)->search($keyword);
                $res = [];
                foreach($orders as $order) {
                    $orderItems = [];
                    foreach($order->orderItems as $item){
                        $orderItems[] = [
                            'product' => $item->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->product->price
                        ];
                    }
                    $res[] = [
                        "id" => $order->id,
                        "employee" => app(EmployeeService::class)->getNameById($order->employee_id),
                        "customer" => app(CustomerService::class)->getNameById($order->customer_id),
                        "created_at" => $order->created_at,
                        "total" => $order->total,
                        "status" => $order->getStatusLabel(),
                        "order_items" => $orderItems
                    ];
                }
                return response()->json($res);
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
