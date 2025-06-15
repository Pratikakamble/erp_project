<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesOrderRequest extends FormRequest
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
            'customer_name'   => 'required|string|max:255|regex:/^[A-Za-z0-9\s\-\.]+$/',

            'products'        => 'required|array|min:1',
            'products.*'      => 'required|exists:products,id',

            'quantities'      => 'required|array|min:1',
            'quantities.*'    => 'required|integer|min:1|regex:/^[0-9]+$/',

            'prices'          => 'required|array|min:1',
            'prices.*'        => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',

            'total'           => 'required|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
        ];

    }


    public function messages(): array
    {
        $messages = [
            'customer_name.required' => 'Customer name is required.',
            'products.*.required' => 'Product selection is required.',
            'quantities.*.required' => 'Quantity is required for each product.',
            'prices.*.required' => 'Price is missing.',
        ];

        // Optional: Loop to provide more specific messages
        if ($this->products) {
            foreach ($this->products as $index => $productId) {
                $humanIndex = $index + 1;
                $messages["quantities.$index.required"] = "Quantity for Product $humanIndex is required.";
                $messages["products.$index.required"] = "Product $humanIndex must be selected.";
            }
        }

        return $messages;
    }
}
