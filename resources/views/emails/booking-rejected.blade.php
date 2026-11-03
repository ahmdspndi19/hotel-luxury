<x-mail::message>
# Bukti pembayaran ditolak

Halo {{ $booking->customer->name }},

Bukti pembayaran untuk booking **#{{ $booking->id }}** di {{ $booking->hotel->name }} belum bisa kami terima.

**Alasan:** {{ $booking->reject_reason }}

Upload ulang bukti yang benar sebelum **{{ $booking->expires_at?->format('d M Y, H:i') }}**. Setelah batas waktu lewat, booking dibatalkan otomatis dan kamar dilepas.

<x-mail::button :url="route('front.hotel.book.payment', $booking)">
Upload Ulang Bukti
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
