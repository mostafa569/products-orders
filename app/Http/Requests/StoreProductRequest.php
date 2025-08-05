<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

     
    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required',
            'name.string' => 'Product name must be a string',
            'name.max' => 'Product name cannot exceed 255 characters',
            'price.required' => 'Product price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be less than 0',
            'stock.required' => 'Stock is required',
            'stock.integer' => 'Stock must be an integer',
            'stock.min' => 'Stock cannot be less than 0',
        ];
    }
}