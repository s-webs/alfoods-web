<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'counterparty_id' => ['nullable', 'integer', 'exists:counterparties,id'],
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'min:0'],
            'items.*.set_id' => ['nullable', 'integer', 'exists:product_sets,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['required', 'string', Rule::in([Product::UNIT_PCS, Product::UNIT_GRAM])],
        ];
    }
}
