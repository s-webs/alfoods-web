<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'opened_at' => ['required', 'date'],
            'closed_at' => ['nullable', 'date', 'after:opened_at'],
        ];
    }
}
