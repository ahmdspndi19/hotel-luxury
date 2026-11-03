@extends('layouts.front')
@section('title', 'Choose a Room')
@section('front')

<section id="content" class="max-w-[640px] lg:max-w-none w-full min-h-screen mx-auto flex flex-col bg-[#F8F8F8] overflow-x-hidden pb-6 lg:pb-16 relative">
    <div class="w-full h-[165px] lg:h-[200px] absolute top-0 bg-[linear-gradient(244.6deg,_#7545FB_14.17%,_#2A3FCC_92.43%)]">
    </div>
    <div class="relative z-10 px-[18px] lg:px-8 w-full lg:max-w-7xl lg:mx-auto flex flex-col gap-6 mt-[60px] lg:mt-10">
      <div class="top-menu flex justify-between items-center">
        <a href="{{ route('front.hotels.details', $hotel) }}" class="">
          <div class="w-[42px] h-[42px] flex shrink-0">
            <img src="{{ asset('assets/images/icons/back.svg') }}" alt="icon">
          </div>
        </a>
        <p class="font-semibold text-lg leading-[28px] text-white text-center">Select Room Type</p>
        <div class="dummy-spacer w-[42px] h-[42px] flex shrink-0">
        </div>
      </div>
      @if ($errors->any())
        <div class="rounded-xl bg-white border border-[#F93F6C] text-[#F93F6C] font-medium text-sm p-4">{{ $errors->first() }}</div>
      @endif
      <div id="result" class="result-card-container flex flex-col gap-[18px] lg:grid lg:grid-cols-3 lg:gap-6">

        @forelse ($hotel->rooms as $room)
        @php
          $left = (session('checkin_at') && session('checkout_at')) ? $room->availableRooms(session('checkin_at'), session('checkout_at')) : null;
        @endphp
        <div class="card-result bg-white rounded-xl overflow-hidden flex flex-col lg:h-full lg:hover:shadow-[0_8px_30px_0_#0A093220] lg:transition-all lg:duration-300">
          <div class="thumbnail-container w-full aspect-[357/160] overflow-hidden flex shrink-0">
            <img src="{{ Storage::url($room->photo) }}" class="object-cover w-full h-full" alt="thumbnail">
          </div>
          <div class="content-container flex flex-col p-4 gap-4 lg:flex-1">
            <div class="title-container flex flex-col gap-[2px]">
              <p class="font-semibold">{{ $room->name }}</p>
              <p class="font-medium text-sm leading-[21px] text-[#757C98]">Max. {{ $room->total_people }} Adult /Room</p>
              @if ($left === null)
                <p class="font-medium text-xs leading-[18px] text-[#757C98]">Pilih tanggal untuk melihat sisa kamar</p>
              @elseif ($left === 0)
                <p class="font-semibold text-xs leading-[18px] text-[#F93F6C]">Kamar habis di tanggal ini</p>
              @else
                <p class="font-semibold text-xs leading-[18px] {{ $left <= 3 ? 'text-[#F98D3F]' : 'text-[#54A917]' }}">Sisa {{ $left }} kamar</p>
              @endif
            </div>
            <div class="facilities-container rounded-xl border border-[#DCDFE6] overflow-hidden">
              <div class="flex flex-col gap-4 p-4 pb-0 mb-4">
                <div class="flex items-center gap-1">
                  <div class="flex shrink-0">
                    <img src="{{ asset('assets/images/icons/wifi-square-grey.svg') }}" alt="icon">
                  </div>
                  <p class="font-medium text-sm leading-[21px] text-[#757C98]">Free Wi-Fi</p>
                </div>
                <div class="flex items-center gap-1">
                  <div class="flex shrink-0">
                    <img src="{{ asset('assets/images/icons/coffee-grey.svg') }}" alt="icon">
                  </div>
                  <p class="font-medium text-sm leading-[21px] text-[#757C98]">Coffee & Tea</p>
                </div>
                <div class="flex items-center gap-1">
                  <div class="flex shrink-0">
                    <img src="{{ asset('assets/images/icons/wind-grey.svg') }}" alt="icon">
                  </div>
                  <p class="font-medium text-sm leading-[21px] text-[#757C98]">Air Conditions</p>
                </div>
              </div>
              <div class="w-full bg-[#F93F6C] p-[10px]">
                <p class="text-center font-semibold text-xs leading-[18px] text-white">Refund & Reschedule not allowed</p>
              </div>
            </div>
            <div class="price-container flex items-center justify-between lg:mt-auto">
              <div class="total-price flex flex-col gap-[2px]">
                <p class="text-[#54A917] font-semibold text-lg leading-[27px]">Rp {{number_format($room->price, 0, ',', '.')}}</p>
                <p class="text-[#757C98] font-semibold text-xs leading-[18px]">/night</p>
              </div>
              <form method="POST" action="{{ route('front.hotel.room.book', [$hotel->slug, $room->id]) }}" onsubmit="const b=this.querySelector('button[type=submit]'); if(b){ if(b.dataset.sent){return false;} b.dataset.sent=1; b.classList.add('opacity-60'); }">
                @csrf
              @if ($left === 0)
                <button type="button" disabled class="w-[138px] h-[48px] bg-[#DCDFE6] p-[12px_24px] rounded-full text-nowrap text-[#757C98] font-semibold text-sm leading-[21px] flex items-center justify-center">Sold Out</button>
              @else
              <button type="submit" class="w-[138px] h-[48px] bg-[#4041DA] p-[12px_24px] rounded-full text-nowrap text-white font-semibold text-sm leading-[21px] flex items-center justify-center">Choose</button>
              @endif
              </form>
            </div>
          </div>
        </div>
        @empty
        <p class="text-center lg:col-span-3 font-semibold text-lg leading-[28px] text-[#757C98]">No rooms available for this hotel.</p>
        @endforelse

      </div>
    </div>
  </section>

@endsection