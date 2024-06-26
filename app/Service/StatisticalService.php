<?php

namespace App\Service;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;

class StatisticalService {
    public function getMonthlyOrders($year)
    {
        return Order::whereYear('created_at', $year)
            ->where("status", 2)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getMonthlyRevenue($year)
    {
        return Order::whereYear('created_at', $year)
            ->where('status', 2)
            ->selectRaw('MONTH(created_at) as month, SUM(pay) as total_revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getMonthlyQuantity($year)
    {
        $monthlyData = OrderItems::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(quantity) as total_quantity')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyData = $monthlyData->map(function ($item) {
            $item->total_quantity = (int)$item->total_quantity;
            return $item;
        });

        return $monthlyData;
    }

    public function getCategoryRevenue()
    {
        return OrderItems::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity * products.price) as total_revenue')
            )
            ->groupBy('categories.name')
            ->orderBy('categories.name')
            ->get();
    }
}
