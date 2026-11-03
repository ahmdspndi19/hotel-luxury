<?php

namespace App\Console\Commands;

use App\Models\HotelBooking;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateProofsToPrivate extends Command
{
    protected $signature = 'bookings:migrate-proofs {--dry-run : Hanya tampilkan, tidak memindahkan}';

    protected $description = 'Pindahkan bukti bayar lama dari disk public ke disk privat (local)';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        $moved = 0;
        $missing = 0;

        HotelBooking::withTrashed()
            ->whereNotNull('proof')
            ->chunkById(200, function ($bookings) use ($public, $private, &$moved, &$missing) {
                foreach ($bookings as $booking) {
                    $path = $booking->proof;

                    if ($private->exists($path)) {
                        continue;
                    }

                    if (! $public->exists($path)) {
                        $missing++;
                        $this->warn("Berkas tidak ditemukan: booking #{$booking->id} ({$path})");
                        continue;
                    }

                    if (! $this->option('dry-run')) {
                        $private->put($path, $public->get($path));
                        $public->delete($path);
                    }

                    $moved++;
                }
            });

        $this->info(($this->option('dry-run') ? '[dry-run] ' : '') . "Dipindahkan: {$moved}, hilang: {$missing}");

        return self::SUCCESS;
    }
}
