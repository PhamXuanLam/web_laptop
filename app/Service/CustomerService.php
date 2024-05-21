<?php

namespace App\Service;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService {

    public function storeCustomer(int $account_id, Customer $customer, $province_id = null, $district_id = null, $commune_id = null)
    {
        DB::beginTransaction();
        try {
            $customer->account_id = $account_id;

            if ($province_id && $district_id && $commune_id) {

                $addressService = app(AddressService::class);

                $address = $addressService->getAddress($province_id, $district_id, $commune_id);

                if($address == null) {
                    $customer->address_id = $addressService->storeAddress($province_id, $district_id, $commune_id);
                } else {
                    $customer->address_id = $address->id;
                }
            }

            $customer->save();
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
    }

    public function getCustomerByAccountId($account_id) {
        return Customer::query()
            ->select(["*"])
            ->with(['address' => function($query) {
                $query->select(['id', 'name', 'province_id', 'district_id', 'commune_id']);
            }])
            ->where("account_id", $account_id)
            ->first();
    }

    public function getCustomerById($id) {
        return Customer::query()
            ->select(["*"])
            ->with(['address' => function($query) {
                $query->select(['id', 'name', 'province_id', 'district_id', 'commune_id']);
            }])
            ->find($id);
    }

    public function getAll()
    {
        return Customer::query()
            ->select(["id", "account_id", "address_id"])
            ->get();
    }

    public function getCustomerByReviews($order) {
        return Customer::select('customers.*', DB::raw('COUNT(product_reviews.id) as review_count'))
            ->join('product_reviews', 'customers.id', '=', 'product_reviews.customer_id')
            ->groupBy('customers.id')
            ->orderBy('review_count', $order)
            ->get();
    }

    public function getCustomerByPurchases($order)
    {
        $query = Order::query();

        switch ($order) {
            case 'day':
                $query->whereDate('created_at', now()->toDateString());
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            default:
                return response()->json(['error' => 'Invalid time filter'], 400);
        }

        $customers = Customer::select('customers.*', DB::raw('COUNT(orders.id) as order_count'))
        ->join('orders', 'customers.id', '=', 'orders.customer_id')
        ->whereIn('orders.id', $query->pluck('orders.id'))
        ->groupBy('customers.id')
        ->orderBy('order_count', 'desc')
        ->get();

        return $customers;
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $customer = $this->getCustomerById($id);
            $account = app(AccountService::class)->getAccountById($customer->account_id);
            app(AccountService::class)->deleteAccount($account);
            $customer->delete();
            DB::commit();
            return true;
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
