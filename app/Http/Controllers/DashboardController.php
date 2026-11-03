<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelBooking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();

        if (! $user->canany(['manage cities', 'manage countries', 'manage hotels', 'manage hotel bookings'])) {
            return redirect()->route('front.index');
        }

        $stats = null;

        if ($user->can('manage hotels')) {
            $stats = [
                'total_bookings' => HotelBooking::count(),
                'active_hotels' => Hotel::count(),
                'total_revenue' => (int) HotelBooking::whereIn('status', HotelBooking::REVENUE_STATUSES)->sum('total_amount'),
                'total_customers' => User::role('customer')->count(),
                'waiting' => HotelBooking::where('status', HotelBooking::WAITING)->count(),
                'today' => HotelBooking::whereDate('created_at', today())->count(),
                'arrivals_today' => HotelBooking::where('status', HotelBooking::PAID)->whereDate('checkin_at', today())->count(),
                'in_house' => HotelBooking::where('status', HotelBooking::CHECKED_IN)->count(),
                'departures_today' => HotelBooking::where('status', HotelBooking::CHECKED_IN)->whereDate('checkout_at', today())->count(),
                'month_revenue' => (int) HotelBooking::whereIn('status', HotelBooking::REVENUE_STATUSES)
                    ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum('total_amount'),
            ];
        }

        return view('dashboard', compact('stats'));
    }

    public function my_bookings(){
        $user = Auth::user();
        $mybookings = HotelBooking::with(['room', 'hotel'])->where('user_id', $user->id)->latest()->paginate(10);
        return view('dashboard.my_bookings', compact('mybookings'));
    }

    public function booking_details(HotelBooking $hotelBooking){
        // FIX: hanya pemilik booking yang boleh lihat detail
        if ($hotelBooking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('dashboard.booking_details', compact('hotelBooking'));
    }

    public function destroy_booking(HotelBooking $hotelBooking){
        // hanya pemilik
        if ($hotelBooking->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $hotelBooking->can_remove) {
            return redirect()->route('dashboard.my-bookings')
                ->withErrors(['booking' => 'Booking ini belum bisa dibatalkan atau dihapus (' . $hotelBooking->status_label . ').']);
        }

        // belum dibayar: batalkan, unit kamar langsung dilepas, riwayat tetap ada
        if (in_array($hotelBooking->effective_status, [HotelBooking::PENDING, HotelBooking::REJECTED], true)) {
            $hotelBooking->update(['status' => HotelBooking::CANCELLED, 'is_paid' => false]);

            return redirect()->route('dashboard.my-bookings')
                ->with('status', 'Booking dibatalkan.');
        }

        // kedaluwarsa, batal, atau sudah selesai menginap: sembunyikan dari riwayat
        $hotelBooking->delete();

        return redirect()->route('dashboard.my-bookings')
            ->with('status', 'Riwayat booking dihapus.');
    }
}
