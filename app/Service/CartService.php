<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CartService {
    /**
     * add product in cart
     */
    public function addToCart(int $product_id, int $customer_id = null)
    {
        $product = Product::find($product_id);

        if(!$product) {
            return [
                "status" => false,
                "message" => "Product does not exist!"
            ];
        }

        if($customer_id != null) {

            DB::beginTransaction();
            try {
                $cart = Cart::firstOrNew([
                    "customer_id" => $customer_id,
                    "product_id" => $product_id
                ]);

                $cart->quantity = $cart->exists ? $cart->quantity + 1 : 1;
                $cart->product_name = $product->name;
                $cart->price = $product->price;
                $cart->total = $cart->quantity * $product->price;
                $cart->status = 1;
                $cart->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
                return [
                    'success' => false,
                    'message' => "An error occurred!",
                    'error' => $e->getMessage()
                ];
            }
        } else {
            $cartFromSession = Session::get('cart', []);

            $cartItem = $cartFromSession[$product_id] ?? [
                "product_id" => $product->id,
                "product_name" => $product->name,
                "price" => $product->price,
                "total" => $product->price,
                "quantity" => 0,
                'status' => 1
            ];

            $cartItem["quantity"]++;
            $cartItem["total"] = $cartItem["quantity"] * $product->price;

            $cartFromSession[$product_id] = $cartItem;
            Session::put('cart', $cartFromSession);
        }

        return [
            "status" => true,
            "message" => "The product has been added to cart"
        ];
    }

    /**
     * Get cart
     */
    public function getCart(int $customer_id = null)
    {
        $cart = $customer_id
            ? Cart::where('customer_id', $customer_id)->get()
            : session()->get('cart', []);

        // Loại bỏ sản phẩm không tồn tại trong db
        $cart = collect($cart)->map(function ($cartItem) {
            $product = Product::find($cartItem['product_id']);

            if ($product) {
                $cartItem['product'] = $product;
                return $cartItem;
            }

            return null;
        })->filter()->keyBy('product_id')->toArray();

        return $cart;
    }

    /**
     * Remove item in carts
     */
    public function removeCart(int $product_id, int $customer_id = null)
    {
        if ($customer_id == null) {
            $cartSession = session('cart', []);
            if (isset($cartSession[$product_id])) {
                unset($cartSession[$product_id]);
                session(['cart' => $cartSession]);

                return [
                    'success' => true,
                    'message' => "The product has been removed from the cart"
                ];
            }
        } else {
            $cartDbItem = Cart::where('customer_id', $customer_id)
            ->where('product_id', $product_id)
            ->first();

            if ($cartDbItem) {
                DB::beginTransaction();
                try {
                    $cartDbItem->delete();
                    DB::commit();

                    return [
                        'success' => true,
                        'message' => "The product has been removed from the cart"
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
    }

     /**
     * Update the value of the shopping cart during operations
     */
    public function updateCart(int $product_id, int $quantity, int $customer_id = null)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return [
                "status" => false,
                "message" => "Product does not exist!"
            ];
        }

        if ($customer_id != null) {
            DB::beginTransaction();
            try {
                $cart = Cart::firstOrNew([
                    'customer_id' => $customer_id,
                    'product_id' => $product_id,
                ]);
                $cart->quantity = $quantity;
                $cart->total = $quantity * $product->price;
                $cart->product_name = $product->name;
                $cart->price = $product->price;
                $cart->status = 1;
                $cart->save();
                DB::commit();

                return [
                    "status" => true,
                    "cart" => $cart
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

        } else {
            $cart = Session::get('cart', []);

            $cartItem = $cart[$product_id] ?? [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $product->price,
                'total' => $product->price,
                'quantity' => 0,
                'status' => 1
            ];
            $cartItem['quantity'] = $quantity;
            $cartItem['total'] = $quantity * $product->price;
            $cart[$product_id] = $cartItem;
            Session::put('cart', $cart);

            return [
                "status" => true,
                "cart" => $cart
            ];
        }
    }

    public function checkout(int $customer_id)
    {
        $cartSession = Session::get('cart', []);
        DB::beginTransaction();
        try {
            foreach($cartSession as $cartItem) {
                $cart = Cart::firstOrNew([
                    "customer_id" => $customer_id,
                    "product_id" => $cartItem['product_id']
                ]);

                $cart->quantity = $cart->exists ? $cart->quantity + $cartItem["quantity"] : $cartItem["quantity"];
                $cart->product_name = $cartItem["product_name"];
                $cart->price = $cartItem["price"];
                $cart->total = $cart->quantity * $cart->price;
                $cart->status = $cartItem["status"];
                $cart->save();
            }
            session()->forget('cart');
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return [
                'success' => false,
                'message' => "An error occurred!",
                'error' => $e->getMessage()
            ];
        }

        $cart = Cart::where("customer_id", $customer_id)->get();

        if (count($cart) > 0) {
            return $cart;
        } else {
            return "You have no items in your shopping cart";
        }
    }
}
