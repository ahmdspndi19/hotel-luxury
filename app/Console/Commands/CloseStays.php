<?php

namespace App\Console\Commands;

use App\Models\HotelBooking;
use Illuminate\Console\Command;

class CloseStays extends Command
{
    protected $signature = 'bookings:close-stays';

    protected $description = 'Check-out otomatis tamu yang lewat tanggal check-out dan tandai tamu yang tidak datang';

    public function handle(): int
    {
        $today = today()->toDateString();

        $out = HotelBooking::where('status', HotelBooking::CHECKED_IN)
            ->where('checkout_at', '<', $today)
            ->update(['status' => HotelBooking::CHECKED_OUT, 'checked_out_at' => now()]);

        $noShow = HotelBooking::where('status', HotelBooking::PAID)
            ->where('checkout_at', '<', $today)
            ->update(['status' => HotelBooking::NO_SHOW]);

        $this->info("Check-out otomatis: {$out}, tidak datang: {$noShow}");

        return self::SUCCESS;
    }
}
