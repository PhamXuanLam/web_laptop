<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Models\Admin;
use App\Models\Image;
use App\Models\Product;
use App\Service\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function index(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {

                $res = app(ImageService::class)->delete($id);
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

    public function create(ImageRequest $request, string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $images = $request->file('image'); // Đây sẽ là một mảng các file

                $res = app(ImageService::class)->store($id, Product::DIRECTORY_IMAGE, $images);
                if ($res == true) {
                    return response()->json([
                        "status" => true,
                        "message" => "Update successfully!",
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

    public function delete(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {

                $res = app(ImageService::class)->delete($id);
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
