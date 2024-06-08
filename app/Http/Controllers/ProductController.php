<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Admin;
use App\Models\Description;
use App\Models\Image;
use App\Models\Product;
use App\Service\DescriptionService;
use App\Service\ImageService;
use App\Service\ProductReviewService;
use App\Service\ProductService;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function brand(string $brand) {
        $products = app(ProductService::class)->getProductByBrand($brand);

        return response()->json([
            "messages" => "List product by brand",
            "products" => $products
        ]);
    }

    public function demands(){
        $demands = app(ProductService::class)->getDemands();
        return response()->json([
            "messages" => "List demand",
            "Demands" => $demands
        ]);
    }

    public function index()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $products = app(ProductService::class)->getAll();
                $response = [];
                foreach($products as $product) {
                    $response[] = [
                        "id" => $product->id,
                        "name" => $product->name,
                        "quantity" => $product->quantity,
                        "created_at" => $product->created_at,
                        "price" => $product->price
                    ];
                }
                return response()->json($response);
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

    public function create(ProductRequest $request)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $params = $request->only([
                    "name" , "price" , "quantity",
                    "category_id", "size",
                    "color", "demand", "brand",
                ]);
                $description = $request->only([
                    'guarantee', 'mass', 'cpu',
                    'screen', 'storage', 'graphics',
                    'battery', 'operating_system', 'ram',
                    'other'
                ]);
                $images = $request->file('image'); // Đây sẽ là một mảng các file
                $product = app(ProductService::class)->store(new Product(), $params);
                app(ImageService::class)->store($product->id, Product::DIRECTORY_IMAGE, $images);
                app(DescriptionService::class)->store(new Description(), $description, $product->id);
                $response = app(ProductService::class)->getProductById($product->id);

                return response()->json($response);
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

    public function update(ProductRequest $request, string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $params = $request->only([
                    "name" , "price" , "quantity",
                    "category_id", "size",
                    "color", "demand", "brand",
                ]);
                $description = $request->only([
                    'guarantee', 'mass', 'cpu',
                    'screen', 'storage', 'graphics',
                    'battery', 'operating_system', 'ram',
                    'other'
                ]);
                $images = $request->file('image');
                $product = app(ProductService::class)->store(Product::find($id), $params);
                app(ImageService::class)->store($product->id, Product::DIRECTORY_IMAGE, $images);
                $temp = app(DescriptionService::class)->getDescriptionByProductId($product->id);
                app(DescriptionService::class)->store($temp, $description);

                $response = app(ProductService::class)->getProductById($id);

                return response()->json($response);
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
                $res = app(ProductService::class)->getProductByKeyword($keyword);
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

    public function filter(string $filter, string $order)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                if ($filter == "bestseller") {
                    $res = app(ProductService::class)->getProductBestseller($order);
                } else {
                    $res = Product::orderBy($filter, $order)->get();
                }
                return response()->json([$res]);
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
                $product = app(ProductService::class)->getProductById($id);

                return response()->json($product);
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
                $res = app(ProductService::class)->delete($id);
                if ($res == true) {
                    return response()->json([
                        "status" => true,
                        "message" => "Delete successfully!",
                    ]);
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "Has error!",
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
}
