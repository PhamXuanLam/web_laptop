<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Order;
use App\Models\OrderItems;
use App\Service\StatisticalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticalController extends Controller
{
    public function sumOrder()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $orders = Order::where('status', 2)->count();
                $total = Order::where('status', 2)->sum('pay');
                return response()->json([
                    "orders" => $orders,
                    "total" => $total
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


    public function sumProduct()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $products = OrderItems::sum('quantity');
                return response()->json([
                    $products
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

    public function monthlyOrders($year)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getMonthlyOrders($year);
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

    public function decisionSupportMonthlyOrders($year)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $orders = app(StatisticalService::class)->getMonthlyOrders($year);
                $ordersArray = $orders->toArray();

                // Tính toán giá trị max và min của total_orders
                $totalOrders = array_column($ordersArray, 'total_orders');
                $maxOrders = max($totalOrders);
                $minOrders = min($totalOrders);
                return response()->json(["max" => $maxOrders, "min" => $minOrders]);
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

    public function decisionSupportMonthlyRevenue($year)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getMonthlyRevenue($year);
                $ordersArray = $res->toArray();

                // Tính toán giá trị max và min của total_orders
                $totalOrders = array_column($ordersArray, 'total_revenue');
                $maxOrders = max($totalOrders);
                $minOrders = min($totalOrders);
                return response()->json(["max" => $maxOrders, "min" => $minOrders]);
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

    public function monthlyRevenue($year)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getMonthlyRevenue($year);
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

    public function decisionSupportMonthlyQuantity($year)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getMonthlyQuantity($year);
                $ordersArray = $res->toArray();

                // Tính toán giá trị max và min của total_orders
                $totalOrders = array_column($ordersArray, 'total_quantity');
                $maxOrders = max($totalOrders);
                $minOrders = min($totalOrders);
                return response()->json(["max" => $maxOrders, "min" => $minOrders]);
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
    public function monthlyQuantity($year)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getMonthlyQuantity($year);
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

    public function decisionSupportCategoryRevenue()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getCategoryRevenue();
                $ordersArray = $res->toArray();

                // Tính toán giá trị max và min của total_orders
                $totalOrders = array_column($ordersArray, 'total_revenue');
                $maxOrders = max($totalOrders);
                $minOrders = min($totalOrders);
                return response()->json(["max" => $maxOrders, "min" => $minOrders]);
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
    public function categoryRevenue()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(StatisticalService::class)->getCategoryRevenue();
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
