<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cashier_id' => ['nullable', 'integer', 'exists:cashiers,id'],
            'shift_id' => ['nullable', 'integer', 'exists:shifts,id'],
            'shopper_id' => ['nullable', 'integer'],
            'counterparty_id' => ['nullable', 'integer', 'exists:counterparties,id'],
            'is_on_credit' => ['nullable', 'boolean'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'min:0'], // 0 = произвольная позиция или сет
            'items.*.set_id' => ['nullable', 'integer', 'exists:product_sets,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['required', 'string', Rule::in([Product::UNIT_PCS, Product::UNIT_GRAM])],
        ];
    }
}
