<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $product = $this->route('product');
        return auth()->check() && 
               (auth()->user()->role === 'admin' || auth()->id() === $product->user_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'sometimes|exists:categories,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|min:10',
            'price' => 'sometimes|numeric|min:0.01|max:999999.99',
            'type' => 'sometimes|in:downloadable,account',
            'download_file' => 'required_if:type,downloadable|nullable|string',
            'account_details' => 'required_if:type,account|nullable|string',
            'stock_quantity' => 'sometimes|integer|min:0|max:9999',
            'is_active' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'images' => 'sometimes|array|max:5',
            'tags' => 'sometimes|array|max:10',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.string' => 'Product title must be a string.',
            'description.min' => 'Description must be at least 10 characters.',
            'price.min' => 'Price must be at least $0.01.',
            'download_file.required_if' => 'Download file is required for downloadable products.',
            'account_details.required_if' => 'Account details are required for account products.',
        ];
    }
}