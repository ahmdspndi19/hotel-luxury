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
            $table->timestamp('checked_in_at')->nullable()->after('paid_at');
            $table->timestamp('checked_out_at')->nullable()->after('checked_in_at');
        });

        DB::table('hotel_bookings')
            ->where('status', 'paid')
            ->where('checkout_at', '<', now()->toDateString())
            ->update(['status' => 'checked_out']);
    }

    public function down(): void
    {
        DB::table('hotel_bookings')
            ->whereIn('status', ['checked_in', 'checked_out', 'no_show'])
            ->update(['status' => 'paid']);

        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropColumn(['checked_in_at', 'checked_out_at']);
        });
    }
};
