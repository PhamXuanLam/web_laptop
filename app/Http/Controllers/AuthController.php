<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) 
    {
       $params = $request->only([
            'username',
            'password',
            'email',
            'birth_day',
            'first_name',
            'last_name',
            'phone',
            'avatar'
        ]);

        $customer = new Customer();
        
    }

    public function login(LoginRequest $request) 
    {
        $response = [];

        if(Auth::guard('account')->attempt([
            "username" => $request->username,
            "password" => $request->password
        ])) {
            $account = Auth::guard('account')->user();
            
            if($account->status === Account::STATUS_ACTIVE) {

                $response["status"] = true;
                $response["message"] = "Login successfully!";
                $response["token"] = $account->createToken('myToken')->accessToken;    
                $response["role"] = $account->role;

            } else {

                $response["status"] = false;
                $response["message"] = "Account inactive!";

            }
           
        } else {
            
            $response["status"] = false;
            $response["message"] = "Username or password not correct!";

        }

        return response()->json($response);
    }

    public function show() 
    {
        $account = Auth::guard('account_api')->user();

        if($account) 
            return response()->json([
                "status" => true,
                "message" => "Profile information",
                "data" => $account,
            ]);

        return response()->json([
            "status" => false,
            "message" => "Please login!",
        ]);
    }

    public function logout() 
    {
        Auth::guard("account_api")->user()->token()->revoke();

        return response()->json([
            "status" => true,
            "message" => "Logged out!",
        ]);
    }
}
