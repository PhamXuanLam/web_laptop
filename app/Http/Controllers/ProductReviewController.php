<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductReviewRequest;
use App\Models\Admin;
use App\Models\ProductReview;
use App\Service\CustomerService;
use App\Service\ProductReviewService;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function create(ProductReviewRequest $request)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            $params = $request->only([
                "product_id",
                "comment",
                'rate'
            ]);
            $params['customer_id'] = app(CustomerService::class)->getCustomerByAccountId($account->id)->id;

            $res = app(ProductReviewService::class)->store(new ProductReview(), $params);
            if($res == true) {
                return response()->json([
                    "status" => true,
                    "message" => "Đánh giá thành công",
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Đánh giá thất bại",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }

    public function update(ProductReviewRequest $request, string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            $params = $request->only([
                "product_id",
                "comment",
                'rate'
            ]);
            $params['customer_id'] = app(CustomerService::class)->getCustomerByAccountId($account->id)->id;
            $review = app(ProductReviewService::class)->getReviewById($id);
            if($params["customer_id"] == $review->customer_id) {
                $res = app(ProductReviewService::class)->store($review, $params);
                if($res == true) {
                    return response()->json([
                        "status" => true,
                        "message" => "Đánh giá thành công",
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Đánh giá thất bại",
                    ]);
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Đánh giá thất bại",
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Please login!",
            ]);
        }
    }

    public function index()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(ProductReview::class)->getAll();
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

    public function delete(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $review = ProductReview::find($id);
                $review->delete();
                return response()->json([
                    true
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
}
