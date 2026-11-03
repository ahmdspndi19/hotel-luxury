<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Tamu mengirim review. Syarat: pemilik booking, lunas, sudah check-out, belum pernah review.
     */
    public function store(Request $request, HotelBooking $hotelBooking)
    {
        if ($hotelBooking->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $hotelBooking->can_review) {
            return redirect()->route('dashboard.booking_details', $hotelBooking)
                ->withErrors(['review' => 'Review hanya bisa dikirim sekali, setelah pembayaran lunas dan check-out.']);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        Review::firstOrCreate(
            ['hotel_booking_id' => $hotelBooking->id],
            [
                'user_id' => Auth::id(),
                'hotel_id' => $hotelBooking->hotel_id,
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        return redirect()->route('dashboard.booking_details', $hotelBooking)
            ->with('status', 'Terima kasih, review kamu terkirim.');
    }

    /** Admin: daftar semua review. */
    public function index()
    {
        $reviews = Review::with(['user', 'hotel', 'booking'])->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /** Admin: sembunyikan atau tampilkan review (tidak dihapus). */
    public function toggle(Review $review)
    {
        $review->update(['is_hidden' => ! $review->is_hidden]);

        return redirect()->route('admin.reviews.index')
            ->with('status', $review->is_hidden ? 'Review disembunyikan.' : 'Review ditampilkan lagi.');
    }
}
