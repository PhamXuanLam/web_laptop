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

    public function getMonthlyQuantityInYear($month)
    {
        return OrderItems::whereMonth('created_at', $month)
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(quantity) as total_quantity')
            )
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->get();
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
