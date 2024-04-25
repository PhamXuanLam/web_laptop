<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService {
    public function order(int $customer_id)
    {
        DB::beginTransaction();
        try {
            $order = new Order();
            $order->customer_id = $customer_id;
            $order->tax = 0;
            $order->discount = 0;
            $order->total = 0;
            $order->pay = 0;
            $order->save();

            $cart = app(CartService::class)->getCart($customer_id);
            $cartProductIds = array_keys($cart);
            $data = $this->orderItems($cart, $order->id);
            Cart::where('customer_id', $customer_id)
                ->whereIn('product_id', $cartProductIds)
                ->delete();

            $order->update($data);
            DB::commit();

            return [
                "status" => true,
                "message" => "You have placed an order successfully and are waiting for the seller's approval!"
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }
    }

    public function orderItems($cart, $order_id)
    {
        $total = 0;
        $orderItems = [];
        $cartProductIds = array_keys($cart);

        foreach ($cartProductIds as $productId) {
            $item = $cart[$productId];
            $orderItems[] = [
                'order_id' => $order_id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'status' => 0,
            ];
            $total += $item['total'];
        }

        DB::beginTransaction();
        try {
            OrderItems::insert($orderItems);
            DB::commit();
            return [
                "total" => $total,
                "pay" => $total
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }
    }
}