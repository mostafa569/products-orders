<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
     

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
        ];
    }

     
    public function messages(): array
    {
        return [
            'name.string' => 'Product name must be a string',
            'name.max' => 'Product name cannot exceed 255 characters',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price cannot be less than 0',
        ];
    }
} 