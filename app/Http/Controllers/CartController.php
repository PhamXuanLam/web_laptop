<?php

namespace App\Http\Controllers;

use App\Service\CartService;
use App\Service\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $cartService;
    protected $customerService;

    public function __construct()
    {
        $this->cartService = app(CartService::class);
        $this->customerService = app(CustomerService::class);
    }

    public function addToCart(Request $request)
    {
        if(Auth::guard('account_api')->check()) {
            $customer = $this->customerService->getCustomerByAccountId(Auth::guard('account_api')->id());
            $response = $this->cartService->addToCart($request->product_id, $customer->id);
        } else {
            $response = $this->cartService->addToCart($request->product_id);
        }

        return response()->json($response);
    }

    public function index()
    {
        if(Auth::guard('account_api')->check()) {
            $customer = $this->customerService->getCustomerByAccountId(Auth::guard('account_api')->id());
            $cart = $this->cartService->getCart($customer->id);
        } else {
            $cart = $this->cartService->getCart();
        }

        return response()->json(["cart" => $cart]);
    }

    public function removeCart(Request $request)
    {
        if(Auth::guard('account_api')->check()) {
            $customer = $this->customerService->getCustomerByAccountId(Auth::guard('account_api')->id());
            $response = $this->cartService->removeCart($request->product_id, $customer->id);
        } else {
            $response = $this->cartService->removeCart($request->product_id);
        }

        return response()->json($response);
    }

    public function updateCart(Request $request)
    {
        if(Auth::guard('account_api')->check()) {
            $customer = $this->customerService->getCustomerByAccountId(Auth::guard('account_api')->id());
            $response = $this->cartService->updateCart($request->product_id, $request->quantity, $customer->id);
        } else {
            $response = $this->cartService->updateCart($request->product_id, $request->quantity);
        }

        return response()->json($response);
    }

    public function checkout() 
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            $customer = $this->customerService->getCustomerByAccountId($account->id);
            $account->address = $customer->address ? $customer->address : null;
            $cart = $this->cartService->checkout($customer->id);
            return response()->json([
                "status" => true,
                "account" => $account,
                "cart" => $cart
            ]);
        }
        return response()->json([
            "status" => false,
            "message" => "Please login!",
        ]);
    }
}
