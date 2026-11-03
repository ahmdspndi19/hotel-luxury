<?php

namespace App\Console\Commands;

use App\Models\HotelBooking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Tandai booking yang lewat batas waktu bayar sebagai expired';

    public function handle(): int
    {
        $count = HotelBooking::whereIn('status', [HotelBooking::PENDING, HotelBooking::REJECTED])
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->update(['status' => HotelBooking::EXPIRED, 'is_paid' => false]);

        $this->info("Booking kedaluwarsa: {$count}");

        return self::SUCCESS;
    }
}
