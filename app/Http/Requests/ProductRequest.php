<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $id = $this->route('product')?->id ?? null;
        return [
            'name' => 'required|string|max:255|regex:/^[A-Za-z0-9\s\-]+$/',
            'sku' => 'required|string|max:255|regex:/^[A-Z0-9\-]+$/|unique:products,sku,' . $id,
            'price' => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'quantity' => 'required|integer|min:0|regex:/^[0-9]+$/',
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Name must only contain letters, numbers, spaces, and hyphens.',
            'sku.regex' => 'SKU must contain only uppercase letters, numbers, and dashes.',
            'price.regex' => 'Price must be a valid number with up to two decimal places.',
            'quantity.regex' => 'Quantity must be a non-negative whole number.',
        ];
    }
}
