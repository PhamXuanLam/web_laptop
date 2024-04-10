<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Auth;

class CustomerRequest extends FormRequest
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
            "username" => "required|max:20|unique:accounts,username," . Auth::id(),
            "email" => "required|email:rfc,dns|max:100|unique:accounts,email," . Auth::id(),
            "phone" => "required|string|min:10|max:12|unique:accounts,phone," . Auth::id(),
            "first_name" => "required|string|max:20",
            "last_name" => "required|string|max:20",
            'birth_day' => "nullable|date",
            'avatar' => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048", 
            'role'=> "nullable|string",
            'province_id' => "nullable|numeric|exists:provinces,id", 
            'district_id' => "nullable|numeric|exists:districts,id", 
            'commune_id' => "nullable|numeric|exists:communes,id"
        ];
    
        // Kiểm tra nếu là "đăng ký" thì thêm quy tắc cho trường "password"
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|confirmed';
        }
    
        return $rules;
    }

    // public function attributes()
    // {
    //     return [
    //         'username' => 'Username',
    //         'password' => 'Password',
    //         'email' => 'Email',
    //         'birth_day' => 'Birth day',
    //         'first_name' => 'First name',
    //         'last_name' => 'Last name',
    //         'phone' => 'Phone'
    //     ];
    // }

    // public function messages()
    // {
    //     return [
    //         'required' => ':attribute is required!',
    //         'min' => ':attribute must be more than :min characters!',
    //         'max' => ':attribute must be less than :min characters!',
    //         'date' => ':attribute incorrect time format!',
    //         'email' => ':attribute incorrect email format!',
    //         'unique' => ':attribute already exist!',
    //         'confirmed' => ':attribute do not match!'
    //     ];
    // }
    
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