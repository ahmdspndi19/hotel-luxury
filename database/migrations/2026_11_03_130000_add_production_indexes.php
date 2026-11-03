<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->index(['hotel_room_id', 'status', 'checkin_at', 'checkout_at'], 'hb_room_status_dates_idx');
            $table->index(['user_id', 'created_at'], 'hb_user_created_idx');
            $table->index(['status', 'expires_at'], 'hb_status_expires_idx');
        });
    }

    public function down(): void
    {
        Schema::table('hotel_bookings', function (Blueprint $table) {
            $table->dropIndex('hb_room_status_dates_idx');
            $table->dropIndex('hb_user_created_idx');
            $table->dropIndex('hb_status_expires_idx');
        });
    }
};
