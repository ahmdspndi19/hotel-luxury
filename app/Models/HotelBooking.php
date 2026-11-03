<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelBooking extends Model
{
    use HasFactory, SoftDeletes;

    public const PENDING = 'pending';
    public const WAITING = 'waiting_verification';
    public const PAID = 'paid';
    public const REJECTED = 'rejected';
    public const EXPIRED = 'expired';
    public const CANCELLED = 'cancelled';

    /** Batas waktu bayar (jam) sejak booking dibuat atau bukti ditolak. */
    public const PAYMENT_WINDOW_HOURS = 2;

    protected $fillable = [
        'user_id',
        'hotel_id',
        'hotel_room_id',
        'checkin_at',
        'checkout_at',
        'total_amount',
        'total_days',
        'is_paid',
        'status',
        'expires_at',
        'paid_at',
        'reject_reason',
        'proof',
    ];

    protected $casts = [
        'checkin_at' => 'date',
        'checkout_at' => 'date',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function customer(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hotel(){
        return $this->belongsTo(Hotel::class, 'hotel_id')->withTrashed();
    }

    public function room(){
        return $this->belongsTo(HotelRoom::class, 'hotel_room_id')->withTrashed();
    }

    public function review(){
        return $this->hasOne(Review::class, 'hotel_booking_id');
    }

    /** Boleh kirim review: lunas, sudah check-out, belum pernah review. */
    public function getCanReviewAttribute(): bool
    {
        return $this->status === self::PAID
            && $this->checkout_at->lt(now()->startOfDay())
            && ! $this->review;
    }

    /**
     * Booking yang masih menahan unit kamar:
     * - paid dan waiting_verification selalu menahan,
     * - pending dan rejected menahan hanya selama batas waktu bayar belum lewat.
     */
    public function scopeBlocking(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->whereIn('status', [self::PAID, self::WAITING])
                ->orWhere(function (Builder $q2) {
                    $q2->whereIn('status', [self::PENDING, self::REJECTED])
                        ->where(function (Builder $q3) {
                            $q3->whereNull('expires_at')->orWhere('expires_at', '>', now());
                        });
                });
        });
    }

    /** Status nyata: pending/rejected yang lewat batas waktu dianggap expired walau job belum jalan. */
    public function getEffectiveStatusAttribute(): string
    {
        if (in_array($this->status, [self::PENDING, self::REJECTED], true)
            && $this->expires_at
            && $this->expires_at->isPast()) {
            return self::EXPIRED;
        }

        return $this->status;
    }

    /** Tamu masih boleh mengunggah bukti. */
    public function isPayable(): bool
    {
        return in_array($this->effective_status, [self::PENDING, self::REJECTED], true);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->effective_status) {
            self::PENDING => 'Waiting for Payment',
            self::WAITING => 'Waiting for Verification',
            self::PAID => 'Paid',
            self::REJECTED => 'Payment Rejected',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
            default => ucfirst((string) $this->status),
        };
    }

    public function getStatusTitleAttribute(): string
    {
        return match ($this->effective_status) {
            self::PAID => 'Booking Success',
            self::WAITING => 'Waiting for Confirmation',
            self::PENDING => 'Waiting for Payment',
            self::REJECTED => 'Payment Rejected',
            self::EXPIRED => 'Booking Expired',
            self::CANCELLED => 'Booking Cancelled',
            default => 'Booking',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->effective_status) {
            self::PAID => '#54A917',
            self::WAITING, self::PENDING => '#F98D3F',
            self::REJECTED => '#F93F6C',
            default => '#757C98',
        };
    }

    /** Tamu boleh membatalkan atau menghapus riwayat booking ini. */
    public function getCanRemoveAttribute(): bool
    {
        $status = $this->effective_status;

        if (in_array($status, [self::PENDING, self::REJECTED, self::EXPIRED, self::CANCELLED], true)) {
            return true;
        }

        return $status === self::PAID && $this->checkout_at->lt(now()->startOfDay());
    }
}
