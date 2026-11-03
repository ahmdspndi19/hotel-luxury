<?php

namespace App\Mail;

use App\Models\HotelBooking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed extends Mailable
{
    use SerializesModels;

    public function __construct(public HotelBooking $booking)
    {
        $this->booking->loadMissing(['hotel.city', 'hotel.country', 'room', 'customer']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Booking #' . $this->booking->id . ' dikonfirmasi');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.booking-confirmed');
    }
}
