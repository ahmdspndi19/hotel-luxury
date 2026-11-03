<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelBookingRequest;
use App\Http\Requests\StorePaymentBookingRequest;
use App\Http\Requests\StoreSearchHotelRequest;
use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\HotelRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    /** Batas lama menginap per booking (malam). */
    private const MAX_NIGHTS = 30;

    public function index(){
        return view('front.index');
    }

    public function hotels(){
        return view('front.hotels');
    }

    public function search_hotels(StoreSearchHotelRequest $request) {
        $request->session()->put('checkin_at', $request->input('checkin_at'));
        $request->session()->put('checkout_at', $request->input('checkout_at'));
        $request->session()->put('keyword', $request->input('keyword'));

        return redirect()->route('front.hotels.list', ['keyword' => $request->input('keyword')]);
    }

    public function list_hotels(string $keyword) {
        // batasi panjang keyword dan escape wildcard LIKE supaya "%" / "_" dicari apa adanya
        $keyword = Str::limit(trim($keyword), 100, '');
        $like = '%' . addcslashes($keyword, '\\%_') . '%';

        // orWhere dibungkus satu grup supaya kondisi pencarian tidak bocor
        $hotels = Hotel::withReviewStats()
            ->with(['rooms', 'city', 'country'])
            ->where(function ($query) use ($like) {
                $query->where('name', 'like', $like)
                    ->orWhereHas('country', fn ($q) => $q->where('name', 'like', $like))
                    ->orWhereHas('city', fn ($q) => $q->where('name', 'like', $like));
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('front.list_hotels', compact('hotels', 'keyword'));
    }

    public function hotel_details(Hotel $hotel) {
        $latestPhotos = $hotel->photos()->orderByDesc('id')->take(3)->get();
        $hotel->loadReviewStats();
        $reviews = $hotel->reviews()->visible()->with(['user', 'booking.room'])->latest()->take(10)->get();

        return view('front.details', compact('hotel', 'latestPhotos', 'reviews'));
    }

    public function hotel_rooms(Hotel $hotel) {
        return view('front.list_hotel_rooms', compact('hotel'));
    }

    public function hotel_room_book(StoreHotelBookingRequest $request, Hotel $hotel, HotelRoom $hotel_room){
        $user = Auth::user();

        // FIX: kamar harus milik hotel di URL
        if ($hotel_room->hotel_id !== $hotel->id) {
            abort(404);
        }

        $checkin_at = $request->session()->get('checkin_at');
        $checkout_at = $request->session()->get('checkout_at');

        // FIX: tanpa tanggal di session (user lewati halaman search) -> kembali ke search
        if (! $checkin_at || ! $checkout_at) {
            return redirect()->route('front.hotels')
                ->withErrors(['checkin_at' => 'Pilih tanggal check-in dan check-out dulu.']);
        }

        $checkinDate = Carbon::parse($checkin_at)->startOfDay();
        $checkoutDate = Carbon::parse($checkout_at)->startOfDay();

        // FIX: total hari wajib integer >= 1
        $totalDays = (int) $checkinDate->diffInDays($checkoutDate);
        if ($checkoutDate->lte($checkinDate) || $totalDays < 1) {
            return redirect()->route('front.hotels')
                ->withErrors(['checkout_at' => 'Check-out harus setelah check-in.']);
        }

        // tanggal di session bisa basi (tab dibiarkan terbuka sampai lewat hari)
        if ($checkinDate->lt(today())) {
            return redirect()->route('front.hotels')
                ->withErrors(['checkin_at' => 'Tanggal check-in sudah lewat. Pilih tanggal baru.']);
        }

        if ($totalDays > self::MAX_NIGHTS) {
            return redirect()->route('front.hotels')
                ->withErrors(['checkout_at' => 'Maksimal menginap ' . self::MAX_NIGHTS . ' malam per booking.']);
        }

        $hotelBookingId = null;
        $isFull = false;

        DB::transaction(function() use ($request, $user, $hotel, $hotel_room, $checkinDate, $checkoutDate, $totalDays, &$hotelBookingId, &$isFull){
            // kunci baris kamar supaya dua booking bersamaan tidak lolos bareng
            $room = HotelRoom::whereKey($hotel_room->id)->lockForUpdate()->first();

            // anti dobel: klik/submit berulang tidak membuat booking baru,
            // pakai booking belum-dibayar yang sama (user, kamar, tanggal)
            $existing = HotelBooking::where('user_id', $user->id)
                ->where('hotel_room_id', $hotel_room->id)
                ->where('checkin_at', $checkinDate->toDateString())
                ->where('checkout_at', $checkoutDate->toDateString())
                ->where('status', HotelBooking::PENDING)
                ->where('expires_at', '>', now())
                ->first();

            if ($existing) {
                $hotelBookingId = $existing->id;
                return;
            }

            if ($room->availableRooms($checkinDate, $checkoutDate) < 1) {
                $isFull = true;
                return;
            }

            $validated = $request->validated();

            $validated['user_id'] = $user->id;
            $validated['hotel_id'] = $hotel->id;
            $validated['hotel_room_id'] = $hotel_room->id;
            $validated['checkin_at'] = $checkinDate->toDateString();
            $validated['checkout_at'] = $checkoutDate->toDateString();
            $validated['total_days'] = $totalDays;
            $validated['total_amount'] = $room->price * $totalDays;
            $validated['is_paid'] = false;
            $validated['status'] = HotelBooking::PENDING;
            $validated['expires_at'] = now()->addHours(HotelBooking::PAYMENT_WINDOW_HOURS);

            $hotelBookingId = HotelBooking::create($validated)->id;
        });

        if ($isFull) {
            return redirect()->route('front.hotels.rooms', $hotel)
                ->withErrors(['room' => 'Kamar ini sudah penuh di tanggal yang dipilih. Pilih tipe kamar lain atau ubah tanggal.']);
        }

        return redirect()->route('front.hotel.book.payment', $hotelBookingId);
    }

    public function hotel_payment(HotelBooking $hotel_booking){
        $user = Auth::user();

        // FIX: user lain tidak boleh buka halaman bayar milik orang lain
        if ($hotel_booking->user_id !== $user->id) {
            abort(403);
        }

        // booking yang sudah dibayar, menunggu verifikasi, kedaluwarsa, atau batal tidak bisa dibayar
        if (! $hotel_booking->isPayable()) {
            return redirect()->route('dashboard.booking_details', $hotel_booking)
                ->withErrors(['booking' => 'Booking ini tidak bisa dibayar (' . $hotel_booking->status_label . ').']);
        }

        return view('front.book_payment', compact('hotel_booking', 'user'));
    }

    public function hotel_payment_store(StorePaymentBookingRequest $request, HotelBooking $hotel_booking){
        $user = Auth::user();

        if ($hotel_booking->user_id !== $user->id) {
            abort(403);
        }

        if (! $hotel_booking->isPayable()) {
            return redirect()->route('dashboard.booking_details', $hotel_booking)
                ->withErrors(['booking' => 'Booking ini tidak bisa dibayar (' . $hotel_booking->status_label . ').']);
        }

        $newProof = null;
        $oldProof = $hotel_booking->proof;

        try {
            DB::transaction(function () use ($request, $hotel_booking, &$newProof) {
                // bukti bayar disimpan di disk privat (tidak bisa dibuka lewat URL publik)
                $newProof = $request->file('proof')->store('proofs', 'local');

                $hotel_booking->update([
                    'proof' => $newProof,
                    'status' => HotelBooking::WAITING,
                    'reject_reason' => null,
                ]);
            });
        } catch (\Throwable $e) {
            if ($newProof) {
                Storage::disk('local')->delete($newProof);
            }
            throw $e;
        }

        // bukti lama (upload ulang setelah ditolak) tidak dipakai lagi; bisa ada di disk lama (public)
        if ($oldProof) {
            Storage::disk('local')->delete($oldProof);
            Storage::disk('public')->delete($oldProof);
        }

        return redirect()->route('front.book_finish');
    }

    public function hotel_book_finish(){
        return view('front.book_finish');
    }
}