<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Description;
use App\Service\DescriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DescriptionController extends Controller
{
    public function create(Request $request, string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $params = $request->only([
                    'guarantee', 'mass', 'cpu',
                    'screen', 'storage', 'graphics',
                    'battery', 'operating_system', 'ram',
                    'other'
                ]);
                $res = app(DescriptionService::class)->store(new Description(), $params, $id);
                if ($res == true) {
                    return response()->json([
                        "status" => true,
                        "message" => "Create successfully!",
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

    public function update(Request $request, string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $params = $request->only([
                    'guarantee', 'mass', 'cpu',
                    'screen', 'storage', 'graphics',
                    'battery', 'operating_system', 'ram',
                    'other'
                ]);
                $description = app(DescriptionService::class)->getDescriptionByProductId($id);
                $res = app(DescriptionService::class)->store($description, $params);
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
                $res = app(DescriptionService::class)->delete($id);
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

    public function index(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $res = app(DescriptionService::class)->getDescriptionByProductId($id);
                return response()->json([
                    $res,
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
