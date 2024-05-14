<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            "username" => "required|max:20|unique:accounts,username," . request()->account_id,
            "email" => "required|email:rfc,dns|max:100|unique:accounts,email," . request()->account_id,
            "phone" => "required|string|min:10|max:12|unique:accounts,phone," . request()->account_id,
            "first_name" => "required|string|max:20",
            "last_name" => "required|string|max:20",
            'birth_day' => "nullable|date",
            'avatar' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
            'role'=> "nullable|string",
            'province_id' => "nullable|numeric|exists:provinces,id",
            'district_id' => "nullable|numeric|exists:districts,id",
            'commune_id' => "nullable|numeric|exists:communes,id",
            'salary' => "required|numeric"
        ];

        // Kiểm tra nếu là "đăng ký" thì thêm quy tắc cho trường "password"
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|confirmed';
        } elseif($this->isMethod('put')) {
            $rules['account_id'] = 'required|numeric';
        } elseif($this->isMethod('delete')) {
            return [
                "id" => 'required|numeric'
            ];
        }

        return $rules;
    }
    /**
     * @overrride
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
