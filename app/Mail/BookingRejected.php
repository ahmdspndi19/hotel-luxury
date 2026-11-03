<?php

namespace App\Mail;

use App\Models\HotelBooking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRejected extends Mailable
{
    use SerializesModels;

    public function __construct(public HotelBooking $booking)
    {
        $this->booking->loadMissing(['hotel', 'room', 'customer']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Bukti pembayaran booking #' . $this->booking->id . ' ditolak');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.booking-rejected');
    }
}
