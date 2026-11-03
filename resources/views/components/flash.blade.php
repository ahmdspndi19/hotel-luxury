{{-- Pesan sukses (session 'status') dan error pertama dari validasi/guard. --}}
@if (session('status') || $errors->any())
    <div class="mb-6 flex flex-col gap-2" role="status" aria-live="polite">
        @if (session('status'))
            <div class="rounded-lg bg-green-50 text-green-700 text-sm font-medium p-3">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="rounded-lg bg-red-50 text-red-700 text-sm font-medium p-3">{{ $errors->first() }}</div>
        @endif
    </div>
@endif
