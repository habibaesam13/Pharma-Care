<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $productId = $this->route('product')->id;

        return [
            'name' => 'sometimes|required|string|unique:products,name,' . $productId,
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'sometimes|required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'brand' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'stock' => 'sometimes|required|integer|min:0',
            'discount_amount' => 'sometimes|nullable|integer|min:0|max:50',
            'category_id' => 'sometimes|required|exists:categories,id',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.unique' => 'This product name is already in use.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'image.required' => 'The product image is required.',
            'image.image' => 'The uploaded file must be a valid image.',
            'image.mimes' => 'Image must be in jpg, jpeg, png, or webp format.',
            'image.max' => 'Image size must not exceed 2MB.',
            'brand.required' => 'The brand name is required.',
            'description.required' => 'The product description is required.',
            'stock.required' => 'The stock quantity is required.',
            'stock.integer' => 'Stock must be an integer.',
            'discount_amount.integer' => 'Discount must be an integer value.',
            'discount_amount.max' => 'Discount cannot be greater than 50%.',
            'discount_amount.min' => 'Discount cannot be negative.',
            'category_id.required' => 'The product category is required.',
            'category_id.exists' => 'The selected category does not exist.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::validationError($validator->errors())
        );
    }
}
