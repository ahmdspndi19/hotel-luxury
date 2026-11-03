<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateHotelRequest extends FormRequest
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
        $hotel = $this->route('hotel');

        return [
            'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($hotel) {
                // slug dibuat dari name dan kolom slug unique -> cek sebelum update (abaikan hotel ini sendiri)
                $taken = DB::table('hotels')
                    ->where('slug', Str::slug($value))
                    ->where('id', '!=', $hotel?->id)
                    ->exists();

                if ($taken) {
                    $fail('Nama hotel sudah dipakai.');
                }
            }],
            'link_gmaps' => ['required', 'string', 'max:2000', 'regex:' . StoreHotelRequest::GMAPS_EMBED_REGEX],
            'address' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'city_id' => ['required', 'integer', 'exists:cities,id,deleted_at,NULL'],
            'country_id' => ['required', 'integer', 'exists:countries,id,deleted_at,NULL'],
            'star_level' => ['required', 'integer', 'between:1,5'],
            'photos' => ['nullable', 'array', 'max:20'],
            'photos.*' => ['image', 'mimes:png,jpg,jpeg', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'link_gmaps.regex' => 'Link harus berupa embed URL Google Maps (https://www.google.com/maps/embed...).',
        ];
    }
}
