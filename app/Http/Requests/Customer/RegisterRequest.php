<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
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
        return [
            "username" => "required|unique:accounts|max:20",
            "password" => "required|confirmed",
            "email" => "required|unique:accounts|email:rfc,dns|max:100",
            "phone" => "required|unique:accounts|string|min:10|max:12",
            "first_name" => "required|string|max:20",
            "last_name" => "required|string|max:20",
        ];
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
