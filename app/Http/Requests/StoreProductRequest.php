<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'new_name' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string'],
            'description' => ['nullable', 'string'],
            'unit' => ['required', 'string', Rule::in([Product::UNIT_PCS, Product::UNIT_GRAM])],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['nullable', 'numeric', 'min:0'],
            'specs' => ['nullable', 'array'],
            'meta' => ['nullable', 'array'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
