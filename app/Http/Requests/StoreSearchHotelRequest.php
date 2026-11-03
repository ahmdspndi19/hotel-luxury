<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSearchHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'keyword' => ['required', 'string', 'max:255'],
            // FIX: check-in tidak boleh di masa lalu
            'checkin_at' => ['required', 'date', 'after_or_equal:today'],
            // FIX: check-out harus setelah check-in (sebelumnya hanya after:today)
            'checkout_at' => ['required', 'date', 'after:checkin_at'],
        ];
    }
}