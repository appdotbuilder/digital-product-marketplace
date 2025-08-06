<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'seller']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'price' => 'required|numeric|min:0.01|max:999999.99',
            'type' => 'required|in:downloadable,account',
            'download_file' => 'required_if:type,downloadable|nullable|string',
            'account_details' => 'required_if:type,account|nullable|string',
            'stock_quantity' => 'required|integer|min:1|max:9999',
            'images' => 'nullable|array|max:5',
            'tags' => 'nullable|array|max:10',
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
            'title.required' => 'Product title is required.',
            'description.required' => 'Product description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'price.required' => 'Product price is required.',
            'price.min' => 'Price must be at least $0.01.',
            'type.required' => 'Product type is required.',
            'download_file.required_if' => 'Download file is required for downloadable products.',
            'account_details.required_if' => 'Account details are required for account products.',
        ];
    }
}