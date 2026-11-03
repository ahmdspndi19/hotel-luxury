<?php

namespace App\Http\Requests;

use App\Models\HotelBooking;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
        $room = $this->route('hotel_room');

        return [
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['sometimes', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            'total_people' => ['required', 'integer', 'between:1,20'],
            'total_rooms' => ['required', 'integer', 'between:1,1000', function ($attribute, $value, $fail) use ($room) {
                // stok tidak boleh turun di bawah jumlah unit yang sedang ditahan booking aktif/mendatang
                if (! $room) {
                    return;
                }

                $held = $room->bookings()
                    ->blocking()
                    ->where('checkout_at', '>', now()->toDateString())
                    ->count();

                if ((int) $value < $held) {
                    $fail("Total kamar tidak boleh di bawah {$held}: masih ada booking aktif yang menahan unit tersebut.");
                }
            }],
            'price' => ['required', 'integer', 'min:1', 'max:1000000000'],
        ];
    }
}
