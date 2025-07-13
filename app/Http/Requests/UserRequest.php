<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;
class UserRequest extends FormRequest
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
            'firstname'=>'required|string|max:50',
            'lastname'=>'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
    public function messages(){
        return[
        'firstname.required' => 'The First name is required.',
        'lastname.required' => 'The Last name is required.',
        'email.required' => 'The email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'The password is required.',
        'password.min' => 'The password must be at least 8 characters.',
        ];
    }
    public function failedValidation(Validator $validator)
{
    throw new HttpResponseException(
        ApiResponse::validationError($validator->errors())
    );
}
}
