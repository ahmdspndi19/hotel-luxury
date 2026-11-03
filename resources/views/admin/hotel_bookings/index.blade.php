<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Hotel Bookings') }}
            </h2>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-0 flex flex-col">
                @if (session('status'))
                    <div class="mx-6 mt-6 rounded-lg bg-green-50 text-green-700 text-sm font-medium p-3">{{ session('status') }}</div>
                @endif
                <div class="p-6 flex flex-col gap-y-2">
 
                    @if ($errors->any())
                        <div class="rounded-lg bg-red-50 text-red-700 text-sm font-medium p-3">{{ $errors->first() }}</div>
                    @endif
                    <form method="GET" class="mb-4 flex items-center gap-3">
                        <select name="status" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm">
                            <option value="">Semua status</option>
                            @foreach (['waiting_verification' => 'Menunggu verifikasi', 'pending' => 'Menunggu pembayaran', 'paid' => 'Lunas', 'rejected' => 'Ditolak', 'expired' => 'Kedaluwarsa', 'cancelled' => 'Dibatalkan'] as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>

                    @forelse($hotel_bookings as $booking)
                    <div class="item-card flex flex-row justify-between items-center py-4 border-b border-gray-100 hover:bg-gray-50 px-4 -mx-4 transition-all duration-200">
                        
                        <div class="flex flex-row items-center gap-x-4 w-2/5">
                            <img src="{{Storage::url($booking->hotel->thumbnail)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] shadow-sm">
                            <div class="flex flex-col">
                                <h3 class="text-indigo-950 text-lg font-bold">
                                    {{$booking->hotel->name}}
                                </h3>
                                <p class="text-slate-500 text-sm mt-1">
                                    {{$booking->hotel->city->name}}, {{$booking->hotel->country->name}}
                                </p>
                            </div>
                        </div> 
                        
                        <div class="hidden md:flex flex-col w-1/5">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Customer</p>
                            <h3 class="text-slate-800 text-base font-semibold">
                                {{$booking->customer->name}}
                            </h3>
                        </div>
                        
                        <div class="hidden md:flex flex-col w-1/5">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Duration</p>
                            <h3 class="text-slate-800 text-base font-semibold">
                                {{number_format($booking->total_days, 0, ',', '.')}} <span class="text-sm font-normal text-gray-500">Days</span>
                            </h3>
                        </div>
                        
                        <div class="hidden md:flex flex-col w-1/5">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Total Price</p>
                            <h3 class="text-indigo-950 text-base font-bold">
                                Rp {{number_format($booking->total_amount, 0, ',', '.')}}
                            </h3>
                            <span class="mt-1 w-fit text-xs font-bold py-1 px-3 rounded-full text-white" style="background: {{ $booking->status_color }}">{{ $booking->status_label }}</span>
                        </div>
                        
                        <div class="hidden md:flex flex-row items-center justify-end gap-x-3 w-1/5">
                            <a href="{{route('admin.hotel_bookings.show', $booking)}}" class="font-semibold py-2 px-6 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-full transition-all duration-300">
                                Details
                            </a>
                            <form action="{{ route('admin.hotel_bookings.destroy', $booking) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus riwayat booking ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-semibold py-2 px-6 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-full transition-all duration-300">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div> 
                    @empty
                    <div class="text-center py-10">
                        <p class="text-slate-500 text-lg">Belum ada pesanan terbaru</p>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>