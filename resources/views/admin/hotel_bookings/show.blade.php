<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Details Booking') }}
            </h2>
            <a href="{{ route('admin.hotel_bookings.index') }}" class="font-semibold py-2 px-6 text-gray-500 hover:text-gray-800 transition-all duration-300">
                &larr; Back
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8 flex flex-col gap-y-6 border border-gray-100">
                
                <!-- Hotel Info -->
                <div class="item-card flex flex-row justify-between items-center pb-6 border-b border-gray-100">
                    <div class="flex flex-row items-center gap-x-4">
                        <img src="{{Storage::url($hotelBooking->hotel->thumbnail)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] shadow-sm">
                        <div class="flex flex-col">
                            <h3 class="text-indigo-950 text-xl font-bold">
                                {{$hotelBooking->hotel->name}}
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">
                                {{$hotelBooking->hotel->city->name}}, {{$hotelBooking->hotel->country->name}}
                            </p>
                        </div>
                    </div>
                    @if($hotelBooking->status === 'waiting_verification')
                    <div class="flex flex-col gap-3 items-end">
                        <form action="{{ route('admin.hotel_bookings.update', $hotelBooking) }}" method="POST" class="m-0">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300 shadow-sm">
                                Approve Payment
                            </button>
                        </form>
                        <form action="{{ route('admin.hotel_bookings.reject', $hotelBooking) }}" method="POST" class="m-0 flex flex-col gap-2 items-end">
                            @csrf
                            @method('PUT')
                            <input type="text" name="reject_reason" required maxlength="255" placeholder="Alasan penolakan" class="rounded-lg border-gray-300 text-sm w-64">
                            <button type="submit" class="font-semibold py-2 px-6 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-full transition-all duration-300">
                                Reject Payment
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                <!-- Room Info -->
                <div class="item-card flex flex-row justify-between items-center pb-6 border-b border-gray-100">
                    <div class="flex flex-row items-center gap-x-4">
                        <img src="{{Storage::url($hotelBooking->room->photo)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] shadow-sm">
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">
                                Booked Room
                            </p>
                            <h3 class="text-indigo-950 text-lg font-bold">
                                {{$hotelBooking->room->name}}
                            </h3>
                        </div>
                    </div> 
                    <div class="flex flex-col text-right">
                        <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Price / Night</p>
                        <h3 class="text-indigo-950 text-lg font-bold">
                            Rp {{number_format($hotelBooking->room->price, 0, ',', '.')}}
                        </h3>
                    </div> 
                </div>

                <!-- Booking Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pb-6 border-b border-gray-100">
                    <div class="flex flex-col gap-y-5">
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-2">Status</p>
                            <span class="w-fit text-xs font-bold py-1.5 px-4 rounded-full text-white" style="background: {{ $hotelBooking->status_color }}">
                                {{ strtoupper($hotelBooking->status_label) }}
                            </span>
                            @if($hotelBooking->reject_reason)
                            <p class="text-sm text-red-600 mt-2">Alasan: {{ $hotelBooking->reject_reason }}</p>
                            @endif
                            @if($hotelBooking->expires_at && $hotelBooking->isPayable())
                            <p class="text-sm text-orange-600 mt-2">Batas bayar: {{ $hotelBooking->expires_at->format('d M Y, H:i') }}</p>
                            @endif
                            @if (session('status'))
                            <p class="text-sm text-green-700 mt-2">{{ session('status') }}</p>
                            @endif
                            @if ($errors->any())
                            <p class="text-sm text-red-600 mt-2">{{ $errors->first() }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Customer Name</p>
                            <h3 class="text-indigo-950 text-lg font-bold">
                                {{$hotelBooking->customer->name}}
                            </h3>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Email</p>
                            <h3 class="text-indigo-950 text-lg font-bold">
                                {{$hotelBooking->customer->email}}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-y-5">
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Quantity</p>
                            <h3 class="text-indigo-950 text-lg font-bold">
                                {{$hotelBooking->room->total_people}} People
                            </h3>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Duration</p>
                            <h3 class="text-indigo-950 text-lg font-bold">
                                {{$hotelBooking->total_days}} Nights
                            </h3>
                        </div>
                        <div class="flex flex-col">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Check-in & Check-out</p>
                            <h3 class="text-indigo-950 text-lg font-bold">
                                {{$hotelBooking->checkin_at->format('M d, Y')}} - {{$hotelBooking->checkout_at->format('M d, Y')}}
                            </h3>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="flex flex-col">
                        <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Total Amount</p>
                        <h3 class="text-indigo-900 text-2xl font-black">
                            Rp {{number_format($hotelBooking->total_amount, 0, ',', '.')}}
                        </h3>
                    </div>
                    <div class="flex flex-col gap-y-3">
                        <h3 class="text-slate-500 text-xs uppercase tracking-wider font-semibold">
                            Payment Proof
                        </h3>
                        <div class="rounded-xl overflow-hidden border border-gray-200 p-1 w-fit">
                            @if($hotelBooking->proof)
                            <img src="{{ route('bookings.proof', $hotelBooking) }}" alt="Bukti pembayaran booking #{{ $hotelBooking->id }}" loading="lazy" class="rounded-lg object-cover w-[250px] h-auto shadow-sm">
                            @else
                            <p class="text-sm text-slate-500 p-4">Belum ada bukti.</p>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>