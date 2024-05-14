<?php

namespace App\Service;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function getAll()
    {
        return Employee::query()
            ->select(["id", "account_id", "address_id", "salary"])
            ->get();
    }

    public function storeEmployee(int $account_id, Employee $employee, $salary, $province_id = null, $district_id = null, $commune_id = null)
    {
        DB::beginTransaction();
        try {
            $employee->account_id = $account_id;
            $employee->salary = $salary;

            if ($province_id && $district_id && $commune_id) {

                $addressService = app(AddressService::class);

                $address = $addressService->getAddress($province_id, $district_id, $commune_id);

                if($address == null) {
                    $employee->address_id = $addressService->storeAddress($province_id, $district_id, $commune_id);
                } else {
                    $employee->address_id = $address->id;
                }
            }

            $employee->save();
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

    public function getEmployeeByAccountId($account_id)
    {
        return Employee::query()
            ->select(["*"])
            ->with(['address' => function($query) {
                $query->select(['id', 'name', 'province_id', 'district_id', 'commune_id']);
            }])
            ->where("account_id", $account_id)
            ->first();
    }

    public function getEmployeeById($id)
    {
        return Employee::query()
        ->select(["*"])
        ->with(['address' => function($query) {
            $query->select(['id', 'name', 'province_id', 'district_id', 'commune_id']);
        }])
        ->find($id);
    }
}
