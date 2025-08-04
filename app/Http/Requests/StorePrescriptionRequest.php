<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
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
            'prescription_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'notes' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'prescription_image.required' => 'Prescription image is required.',
            'prescription_image.image' => 'The file must be an image.',
            'prescription_image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
            'prescription_image.max' => 'The image may not be greater than 2048 kilobytes.',
        ];
    }

   public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::validationError($validator->errors())
        );
    }
}
