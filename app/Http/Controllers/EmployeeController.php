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
                        'id' => $employee->id,
                        'first_name' => $account->first_name,
                        'last_name' => $account->last_name,
                        'birth_day' => $account->birth_day,
                        'email' => $account->email,
                        'address' => $address->name,
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
            'first_name', 'last_name', "password",
            'phone','birth_day', 'avatar', 'role',
            'province_id', 'district_id', 'commune_id', "salary"
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

    public function update(EmployeeRequest $request, string $id)
    {
        $params = $request->only([
            'username', 'email',
            'first_name', 'last_name',
            'phone','birth_day', 'avatar', 'role',
            'province_id', 'district_id', 'commune_id', "salary"
        ]);

        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $account_id = app(EmployeeService::class)->getEmployeeById($id)->account_id;
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

    public function delete(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                app(EmployeeService::class)->deleteEmployee($id);
                return response()->json([
                    "status" => true,
                    "message" => "Delete successfully!",
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

    public function show(string $id)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $employee = app(EmployeeService::class)->getEmployeeById($id);
                $employee->info  = app(AccountService::class)->getAccountByAccountId($employee->account_id);
                return response()->json([
                    "status" => true,
                    "message" => "Employee detail",
                    "employee" => $employee
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

    public function search(string $keyword)
    {
        $account = Auth::guard('account_api')->user();
        if($account) {
            if($account->role === Admin::ADMIN_ROLE) {
                $accounts = app(AccountService::class)->getEmployeeByKeyword($keyword);
                $response = [];
                foreach($accounts as $account) {
                    $employee = app(EmployeeService::class)->getEmployeeByAccountId($account->id);
                    $response[] = [
                        'id' => $employee->id,
                        'first_name' => $account->first_name,
                        'last_name' => $account->last_name,
                        'birth_day' => $account->birth_day,
                        'email' => $account->email,
                        'address' => $employee->address->name,
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
}
