<?php

namespace App\Service;

use App\Models\Order;

class StatisticalService {
    public function getMonthlyOrders($year)
    {
        return Order::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
