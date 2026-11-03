<?php

namespace App\Http\Controllers;

use App\Models\HotelBooking;
use App\Mail\BookingConfirmed;
use App\Mail\BookingRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class HotelBookingController extends Controller
{
    /**
     * Daftar booking. Yang menunggu verifikasi tampil paling atas.
     */
    public function index(Request $request)
    {
        $query = HotelBooking::with(['hotel.city', 'hotel.country', 'customer'])
            ->orderByRaw("status = ? desc", [HotelBooking::WAITING])
            ->orderByDesc('id');

        $filter = $request->validate([
            'status' => ['nullable', Rule::in([
                HotelBooking::PENDING, HotelBooking::WAITING, HotelBooking::PAID,
                HotelBooking::REJECTED, HotelBooking::EXPIRED, HotelBooking::CANCELLED,
                HotelBooking::CHECKED_IN, HotelBooking::CHECKED_OUT, HotelBooking::NO_SHOW,
            ])],
        ]);

        if (! empty($filter['status'])) {
            $query->where('status', $filter['status']);
        }

        $hotel_bookings = $query->paginate(10)->withQueryString();

        return view('admin.hotel_bookings.index', compact('hotel_bookings'));
    }

    public function show(HotelBooking $hotelBooking)
    {
        return view('admin.hotel_bookings.show', compact('hotelBooking'));
    }

    /**
     * Terima pembayaran: hanya booking yang menunggu verifikasi dan punya bukti.
     */
    public function update(Request $request, HotelBooking $hotelBooking)
    {
        // kunci baris + cek ulang di dalam transaksi supaya dua admin tidak memproses booking yang sama
        $approved = DB::transaction(function () use ($hotelBooking) {
            $locked = HotelBooking::whereKey($hotelBooking->id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== HotelBooking::WAITING || ! $locked->proof) {
                return false;
            }

            $locked->update([
                'status' => HotelBooking::PAID,
                'is_paid' => true,
                'reject_reason' => null,
                'expires_at' => null,
                'paid_at' => now(),
            ]);

            return true;
        });

        if (! $approved) {
            return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
                ->withErrors(['status' => 'Booking ini tidak sedang menunggu verifikasi.']);
        }

        $hotelBooking->refresh();
        $this->notify($hotelBooking, new BookingConfirmed($hotelBooking));

        return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
            ->with('status', 'Pembayaran diterima. Email konfirmasi dikirim ke tamu.');
    }

    /**
     * Tolak bukti: alasan wajib. Tamu mendapat jendela bayar baru untuk upload ulang.
     */
    public function reject(Request $request, HotelBooking $hotelBooking)
    {
        $data = $request->validate([
            'reject_reason' => ['required', 'string', 'max:255'],
        ]);

        $rejected = DB::transaction(function () use ($hotelBooking, $data) {
            $locked = HotelBooking::whereKey($hotelBooking->id)->lockForUpdate()->first();

            if (! $locked || $locked->status !== HotelBooking::WAITING) {
                return false;
            }

            $locked->update([
                'status' => HotelBooking::REJECTED,
                'is_paid' => false,
                'reject_reason' => $data['reject_reason'],
                'expires_at' => now()->addHours(HotelBooking::PAYMENT_WINDOW_HOURS),
            ]);

            return true;
        });

        if (! $rejected) {
            return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
                ->withErrors(['status' => 'Booking ini tidak sedang menunggu verifikasi.']);
        }

        $hotelBooking->refresh();
        $this->notify($hotelBooking, new BookingRejected($hotelBooking));

        return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
            ->with('status', 'Bukti ditolak. Tamu bisa upload ulang.');
    }

    public function checkIn(HotelBooking $hotelBooking)
    {
        $done = DB::transaction(function () use ($hotelBooking) {
            $locked = HotelBooking::whereKey($hotelBooking->id)->lockForUpdate()->first();

            if (! $locked || ! $locked->can_check_in) {
                return false;
            }

            $locked->update([
                'status' => HotelBooking::CHECKED_IN,
                'checked_in_at' => now(),
            ]);

            return true;
        });

        if (! $done) {
            return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
                ->withErrors(['status' => 'Tamu belum bisa check-in. Booking harus lunas dan tanggalnya sudah masuk masa menginap.']);
        }

        return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
            ->with('status', 'Tamu sudah check-in.');
    }

    public function checkOut(HotelBooking $hotelBooking)
    {
        $done = DB::transaction(function () use ($hotelBooking) {
            $locked = HotelBooking::whereKey($hotelBooking->id)->lockForUpdate()->first();

            if (! $locked || ! $locked->can_check_out) {
                return false;
            }

            $locked->update([
                'status' => HotelBooking::CHECKED_OUT,
                'checked_out_at' => now(),
            ]);

            return true;
        });

        if (! $done) {
            return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
                ->withErrors(['status' => 'Booking ini belum check-in, jadi belum bisa check-out.']);
        }

        return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
            ->with('status', 'Tamu sudah check-out. Kamar kembali tersedia.');
    }

    public function noShow(HotelBooking $hotelBooking)
    {
        $done = DB::transaction(function () use ($hotelBooking) {
            $locked = HotelBooking::whereKey($hotelBooking->id)->lockForUpdate()->first();

            if (! $locked || ! $locked->can_mark_no_show) {
                return false;
            }

            $locked->update(['status' => HotelBooking::NO_SHOW]);

            return true;
        });

        if (! $done) {
            return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
                ->withErrors(['status' => 'Booking ini belum bisa ditandai tidak datang.']);
        }

        return redirect()->route('admin.hotel_bookings.show', $hotelBooking)
            ->with('status', 'Booking ditandai tamu tidak datang. Kamar kembali tersedia.');
    }

    /**
     * Soft delete. Booking lunas yang belum selesai menginap tidak boleh dihapus.
     */
    public function destroy(HotelBooking $hotelBooking)
    {
        if (in_array($hotelBooking->status, [HotelBooking::PAID, HotelBooking::CHECKED_IN], true)
            && $hotelBooking->checkout_at->gte(now()->startOfDay())) {
            return redirect()->route('admin.hotel_bookings.index')
                ->withErrors(['booking' => 'Booking lunas baru bisa dihapus setelah tanggal check-out.']);
        }

        DB::transaction(function () use ($hotelBooking) {
            $hotelBooking->delete();
        });

        return redirect()->route('admin.hotel_bookings.index')
            ->with('status', 'Riwayat booking dihapus.');
    }

    /**
     * Kirim email ke tamu. Gagal kirim tidak boleh membatalkan keputusan admin.
     */
    private function notify(HotelBooking $booking, $mailable): void
    {
        try {
            Mail::to($booking->customer->email)->send($mailable);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
