<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $response = [];

        if(Auth::guard('account')->attempt([
            "username" => $request->username,
            "password" => $request->password
        ])) {
            $account = Auth::guard('account')->user();
            $response["status"] = true;
            $response["message"] = "Login successfully!";
            $response["token"] = $account->createToken('myToken')->accessToken;
            $response["role"] = $account->role;
        } else {
            $response["status"] = false;
            $response["message"] = "Username or password not correct!";
        }

        return response()->json($response);
    }

    public function logout()
    {
        Auth::guard("account_api")->user()->token()->revoke();

        return response()->json([
            "status" => true,
            "message" => "Logged out!",
        ]);
    }

    public function getNameAdmin()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $name = $account->first_name . " " . $account->last_name;
                return response()->json([
                    $name
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
