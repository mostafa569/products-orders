<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ];
    }

    
    public function messages(): array
    {
        return [
            'status.required' => 'Order status is required',
            'status.string' => 'Order status must be a string',
            'status.in' => 'Order status must be: pending, processing, completed, cancelled',
        ];
    }
}