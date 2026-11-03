<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_booking_id',
        'user_id',
        'hotel_id',
        'rating',
        'comment',
        'is_hidden',
    ];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_hidden', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class)->withTrashed();
    }

    /** Booking boleh sudah disembunyikan tamu (soft delete); review tetap tampil. */
    public function booking()
    {
        return $this->belongsTo(HotelBooking::class, 'hotel_booking_id')->withTrashed();
    }
}
