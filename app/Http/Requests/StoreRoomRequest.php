<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'total_people' => ['required', 'integer', 'between:1,20'],
            'total_rooms' => ['required', 'integer', 'between:1,1000'],
            'price' => ['required', 'integer', 'min:1', 'max:1000000000'],
        ];
    }
}
