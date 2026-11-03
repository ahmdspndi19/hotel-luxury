<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class HotelRoom extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'photo',
        'price',
        'total_people',
        'total_rooms',
        'hotel_id',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookings()
    {
        return $this->hasMany(HotelBooking::class, 'hotel_room_id');
    }

    /** Ada booking yang masih menahan tipe kamar ini (belum lewat tanggal check-out). */
    public function hasActiveBookings(): bool
    {
        return $this->bookings()
            ->blocking()
            ->where('checkout_at', '>=', now()->toDateString())
            ->exists();
    }

    /**
     * Jumlah unit kamar yang sudah terpakai pada rentang tanggal.
     * Dua rentang bentrok jika: checkin_lama < checkout_baru DAN checkout_lama > checkin_baru.
     */
    public function bookedCount($checkin, $checkout): int
    {
        return $this->bookings()
            ->blocking()
            ->where('checkin_at', '<', Carbon::parse($checkout)->toDateString())
            ->where('checkout_at', '>', Carbon::parse($checkin)->toDateString())
            ->count();
    }

    public function availableRooms($checkin, $checkout): int
    {
        return max(0, $this->total_rooms - $this->bookedCount($checkin, $checkout));
    }
}
