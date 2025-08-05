<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
     

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }

     
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Customer name is required',
            'customer_name.string' => 'Customer name must be a string',
            'customer_name.max' => 'Customer name cannot exceed 255 characters',
            'items.required' => 'Order items are required',
            'items.array' => 'Order items must be an array',
            'items.min' => 'Order must contain at least one item',
            'items.*.product_id.required' => 'Product ID is required',
            'items.*.product_id.exists' => 'Product not found',
            'items.*.quantity.required' => 'Quantity is required',
            'items.*.quantity.integer' => 'Quantity must be an integer',
            'items.*.quantity.min' => 'Quantity must be at least 1',
        ];
    }
}