<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AccountService {

    public function storeAccount($params, Account $account)
    {
        DB::beginTransaction();
        try {

            if(isset($params['password']) && $params['password']) {
                $account->password = Hash::make($params['password']);
            }

            if(isset($params['role']) && $params['role']) {
                $account->role = $params['role'];
            } else {
                $account->role = Customer::CUSTOMER_ROLE;
            }

            if(isset($params['salary'])) {
                $salary = $params['salary'];
                unset($params['salary']);
            }

            if(isset($params['province_id'])) {
                $province_id = $params['province_id'];
                unset($params['province_id']);
            }

            if(isset($params['district_id'])) {
                $district_id = $params['district_id'];
                unset($params['district_id']);
            }

            if(isset($params['commune_id'])) {
                $commune_id = $params['commune_id'];
                unset($params['commune_id']);
            }

            $account->fill($params);

            $account->save();

            if($account->role === Customer::CUSTOMER_ROLE) {

                $customerService = app(CustomerService::class);

                $customer = $customerService->getCustomerByAccountId($account->id);

                if ($customer) {
                    if(isset($province_id) && isset($district_id) && isset($commune_id)) {
                        $customerService->storeCustomer($account->id, $customer, $province_id, $district_id, $commune_id);
                    } else {
                        $customerService->storeCustomer($account->id, $customer);
                    }
                } else {
                    $customerService->storeCustomer($account->id, new Customer());
                }

            } elseif($account->role === Employee::EMPLOYEE_ROLE) {

                $employeeService = app(EmployeeService::class);

                $employee = $employeeService->getEmployeeByAccountId($account->id);

                if ($employee) {
                    if(isset($province_id) && isset($district_id) && isset($commune_id)) {
                        $employeeService->storeEmployee($account->id, $employee, $salary, $province_id, $district_id, $commune_id);
                    } else {
                        $employeeService->storeEmployee($account->id, $employee, $salary);
                    }
                } else {
                    $employeeService->storeEmployee($account->id, new Employee(), $salary);
                }
            }
            DB::commit();
            return $account;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("File: ".$e->getFile().'---Line: '.$e->getLine()."---Message: ".$e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    public function getAccountByAccountId($account_id)
    {
        return Account::query()
            ->select(["username", "first_name", "last_name", "birth_day", "email", "phone", 'avatar'])
            ->find($account_id);
    }

    public function getAccountById($id) {
        return Account::query()
            ->find($id);
    }
}
