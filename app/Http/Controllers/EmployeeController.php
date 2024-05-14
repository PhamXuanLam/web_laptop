<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Service\AccountService;
use App\Service\AddressService;
use App\Service\EmployeeService;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $employees = app(EmployeeService::class)->getAll();
                $response = [];
                foreach($employees as $employee) {
                    $account = app(AccountService::class)->getAccountByAccountId($employee->account_id);
                    $address = app(AddressService::class)->getAddress("", "", "", $employee->address_id);
                    $response[] = [
                        'employee' => $employee,
                        'account' => $account,
                        'address' => $address
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

    public function register(EmployeeRequest $request)
    {
        $params = $request->only([
            'username', 'email',
            'first_name', 'last_name',
            'phone', 'password', 'role', "salary"
        ]);

        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $account_new = app(AccountService::class)->storeAccount($params, new Account());
                $account_new->employee = app(EmployeeService::class)->getEmployeeByAccountId($account_new->id);
                return response()->json([
                    "status" => true,
                    "message" => "Register successfully!",
                    "data" => $account_new,
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

    public function update(EmployeeRequest $request)
    {
        $params = $request->only([
            'username', 'email', 'account_id',
            'first_name', 'last_name',
            'phone','birth_day', 'avatar', 'role',
            'province_id', 'district_id', 'commune_id', "salary"
        ]);

        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $account_id = $params['account_id'];
                unset($params['account_id']);
                $temp = app(AccountService::class)->getAccountById($account_id);
                $account_update = app(AccountService::class)->storeAccount($params, $temp);
                $employee_update = app(EmployeeService::class)->getEmployeeByAccountId($account_update->id);
                $account_update->address = $employee_update->address ? $employee_update->address : null;
                return response()->json([
                    "status" => true,
                    "message" => "Update successfully!",
                    "data" => $account_update,
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

    // public function delete(EmployeeRequest $request)
    // {
    //     $account = Auth::guard('account_api')->user();
    //     if($account) {
    //         if($account->role === Admin::ADMIN_ROLE) {

    //             return response()->json([
    //                 "status" => true,
    //                 "message" => "Update successfully!",
    //                 "data" => $account_update,
    //             ]);
    //         } else {
    //             return response()->json([
    //                 "status" => false,
    //                 "message" => "You do not have access!",
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             "status" => false,
    //             "message" => "Please login!",
    //         ]);
    //     }
    // }
}
