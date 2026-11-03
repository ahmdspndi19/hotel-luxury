<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BookingProofController extends Controller
{
    /**
     * Bukti bayar bersifat privat: hanya pemilik booking atau admin booking yang boleh melihat.
     */
    public function show(HotelBooking $hotelBooking): Response
    {
        $user = Auth::user();

        abort_unless(
            $user->id === $hotelBooking->user_id || $user->can('manage hotel bookings'),
            403
        );

        abort_if(! $hotelBooking->proof, 404);

        // file baru ada di disk privat; file lama (sebelum patch ini) masih di disk public
        foreach (['local', 'public'] as $disk) {
            if (Storage::disk($disk)->exists($hotelBooking->proof)) {
                return Storage::disk($disk)->response($hotelBooking->proof, null, [
                    'Cache-Control' => 'private, max-age=0, must-revalidate',
                    'X-Content-Type-Options' => 'nosniff',
                ]);
            }
        }

        abort(404);
    }
}
