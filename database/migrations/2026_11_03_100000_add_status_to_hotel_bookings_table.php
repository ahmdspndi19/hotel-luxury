<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->after('is_paid')->index();
            $table->timestamp('expires_at')->nullable()->after('status');
            $table->string('reject_reason')->nullable()->after('expires_at');
            $table->string('proof')->nullable()->change();
        });

        // Data lama: placeholder 'dummytrx.png' berarti belum ada bukti.
        DB::table('hotel_bookings')->where('proof', 'dummytrx.png')->update(['proof' => null]);

        DB::table('hotel_bookings')
            ->where('is_paid', true)
            ->update(['status' => 'paid']);

        DB::table('hotel_bookings')
            ->where('is_paid', false)
            ->whereNotNull('proof')
            ->update(['status' => 'waiting_verification']);

        // Booking lama yang belum ada bukti diberi waktu 24 jam sebagai masa tenggang.
        DB::table('hotel_bookings')
            ->where('is_paid', false)
            ->whereNull('proof')
            ->update(['status' => 'pending', 'expires_at' => now()->addDay()]);
    }

    public function down(): void
    {
        DB::table('hotel_bookings')->whereNull('proof')->update(['proof' => 'dummytrx.png']);

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn(['status', 'expires_at', 'reject_reason']);
            $table->string('proof')->nullable(false)->change();
        });
    }
};
