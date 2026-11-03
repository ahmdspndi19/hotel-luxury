<x-mail::message>
# Pembayaran diterima

Halo {{ $booking->customer->name }},

Booking kamu sudah dikonfirmasi. Tunjukkan email ini saat check-in.

<x-mail::table>
| Detail | Keterangan |
| :----- | :--------- |
| Order ID | #{{ $booking->id }} |
| Hotel | {{ $booking->hotel->name }} |
| Lokasi | {{ $booking->hotel->city->name }}, {{ $booking->hotel->country->name }} |
| Tipe kamar | {{ $booking->room->name }} |
| Check-in | {{ $booking->checkin_at->format('d M Y') }} |
| Check-out | {{ $booking->checkout_at->format('d M Y') }} ({{ $booking->total_days }} malam) |
| Total dibayar | Rp {{ number_format($booking->total_amount, 0, ',', '.') }} |
</x-mail::table>

<x-mail::button :url="route('dashboard.booking_details', $booking)">
Lihat Detail Booking
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
