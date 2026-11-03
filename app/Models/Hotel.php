<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'link_gmaps',
        'city_id',
        'country_id',
        'address',
        'star_level',
    ];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function photos(){
        return $this->hasMany(HotelPhoto::class);
    }

    public function rooms(){
        return $this->hasMany(HotelRoom::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function bookings(){
        return $this->hasMany(HotelBooking::class);
    }

    /** Ada booking yang masih menahan kamar hotel ini (belum lewat tanggal check-out). */
    public function hasActiveBookings(): bool
    {
        return $this->bookings()
            ->blocking()
            ->where('checkout_at', '>=', now()->toDateString())
            ->exists();
    }

    /** Tambah reviews_count dan reviews_avg_rating (hanya review yang tampil). */
    public function scopeWithReviewStats($query){
        return $query
            ->withCount(['reviews as reviews_count' => fn ($q) => $q->where('is_hidden', false)])
            ->withAvg(['reviews as reviews_avg_rating' => fn ($q) => $q->where('is_hidden', false)], 'rating');
    }

    public function loadReviewStats(): static {
        return $this
            ->loadCount(['reviews as reviews_count' => fn ($q) => $q->where('is_hidden', false)])
            ->loadAvg(['reviews as reviews_avg_rating' => fn ($q) => $q->where('is_hidden', false)], 'rating');
    }

    public function getLowestRoomPrice(){
        $minPrice = $this->rooms()->min('price');
        return $minPrice ?? 0;
    }
}
