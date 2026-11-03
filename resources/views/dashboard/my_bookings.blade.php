@extends('layouts.front')
@section('title', 'My Bookings')
@section('robots', 'noindex,nofollow')
@section('front')

<section id="content" class="max-w-[640px] lg:max-w-none w-full min-h-screen mx-auto flex flex-col bg-[#F8F8F8] overflow-x-hidden pb-6 lg:pb-16 relative">
    <div class="w-full h-[203px] lg:h-[220px] absolute top-0 bg-[linear-gradient(244.6deg,_#7545FB_14.17%,_#2A3FCC_92.43%)]">
    </div>
    <div class="relative z-10 px-[18px] lg:px-8 w-full lg:max-w-5xl lg:mx-auto flex flex-col gap-6 mt-[60px] lg:mt-10 flex-1">
      <div class="top-menu flex justify-between items-center">
        <div class="dummy-spacer w-[42px] h-[42px] flex shrink-0">
        </div>
        <p class="font-semibold text-lg leading-[28px] text-white text-center">Rencent Activity</span></p>
        <div class="dummy-spacer w-[42px] h-[42px] flex shrink-0">
        </div>
      </div>
      <div id="Activity-list" class="result-card-container flex flex-col gap-[18px]">

      @if (session('status'))
        <div class="rounded-xl bg-white border border-[#54A917] text-[#54A917] font-medium text-sm p-4">{{ session('status') }}</div>
      @endif
      @if ($errors->any())
        <div class="rounded-xl bg-white border border-[#F93F6C] text-[#F93F6C] font-medium text-sm p-4">{{ $errors->first() }}</div>
      @endif
        @forelse ($mybookings as $booking)
        <div class="activity-result bg-white rounded-xl overflow-hidden flex flex-col gap-4">
          <div class="flex items-center justify-between p-4 pb-0">
            <div class="flex flex-col gap-[2px]">
              <p class="font-semibold" style="color: {{ $booking->status_color }}">{{ $booking->status_title }}</p>
              <p class="font-medium text-xs leading-[18px] text-[#757C98]">Order ID : {{ $booking->id }}</p>
            </div>
            <button type="button" class="flex shrink-0" aria-label="Menu booking" disabled>
              <img src="{{asset('assets/images/icons/more.svg')}}" alt="">
            </button>
          </div>
          <hr class="border-[#DCDFE6]">
          <div class="hotel-details flex items-center gap-3 px-4">
            <div class="thumbnail-container w-[100px] h-[120px] flex shrink-0 rounded-xl overflow-hidden">
              <img src="{{ Storage::url($booking->room->photo) }}" class="object-cover w-full h-full" alt="Foto kamar {{ $booking->room->name }}" loading="lazy" decoding="async">
            </div>
            <div class="hotel-info flex flex-col gap-[6px] h-fit">
              <div class="ratings-container flex items-center gap-[2px]">
                <div class="star-container flex items-center">
                  <div class="flex shrink-0 w-[18px] h-[18px] p-[2px]">
                    <img src="{{asset('assets/images/icons/Star.svg')}}" alt="star">
                  </div>
                  <div class="flex shrink-0 w-[18px] h-[18px] p-[2px]">
                    <img src="{{asset('assets/images/icons/Star.svg')}}" alt="star">
                  </div>
                  <div class="flex shrink-0 w-[18px] h-[18px] p-[2px]">
                    <img src="{{asset('assets/images/icons/Star.svg')}}" alt="star">
                  </div>
                  <div class="flex shrink-0 w-[18px] h-[18px] p-[2px]">
                    <img src="{{asset('assets/images/icons/Star.svg')}}" alt="star">
                  </div>
                  <div class="flex shrink-0 w-[18px] h-[18px] p-[2px]">
                    <img src="{{asset('assets/images/icons/Star-half.svg')}}" alt="star">
                  </div>
                </div>
                <p class="rating font-semibold text-sm leading-[21px]">4.5/5.0</p>
              </div>
              <p class="font-semibold">{{ $booking->hotel->name }}</p>
              <div class="badge flex items-center gap-3">
                <div class="flex items-center gap-1">
                  <div class="flex shrink-0 w-4 h-4">
                    <img src="{{asset('assets/images/icons/location-grey.svg')}}" alt="icon">
                  </div>
                  <p class="font-medium text-sm leading-[21px] text-[#757C98]">{{ $booking->hotel->city->name }}</p>
                </div>
                <div class="flex items-center gap-1">
                  <div class="flex shrink-0 w-4 h-4">
                    <img src="{{asset('assets/images/icons/star-outline-grey.svg')}}" alt="icon">
                  </div>
                  <p class="font-medium text-sm leading-[21px] text-[#757C98]">{{ $booking->hotel->star_level }} Star</p>
                </div>
              </div>
              <div class="total-price flex gap-[2px] items-center">
                <p class="text-[#54A917] font-semibold text-lg leading-[27px]">Rp {{number_format($booking->room->price, 0, ',', '.')}}</p>
                <p class="text-[#757C98] font-semibold text-xs leading-[18px]">/night</p>
              </div>
            </div>
          </div>
          <a href="{{ route('dashboard.booking_details', $booking) }}" class="flex items-center justify-center font-semibold p-[12px_24px] rounded-lg h-12 bg-[#4041DA17] text-[#4041DA] m-4 mt-0">Booking Details</a>
          @if ($booking->isPayable())
          <a href="{{ route('front.hotel.book.payment', $booking) }}" class="flex items-center justify-center font-semibold p-[12px_24px] rounded-lg h-12 bg-[#4041DA] text-white mx-4 mb-4 -mt-2">Upload Payment Proof</a>
          @endif
          @if ($booking->can_remove)
          <form method="POST" action="{{ route('dashboard.my-bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus riwayat booking ini?')" class="mx-4 mb-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full flex items-center justify-center font-semibold p-[12px_24px] rounded-lg h-12 bg-[#F93F6C17] text-[#F93F6C]">{{ $booking->isPayable() ? 'Cancel Booking' : 'Delete' }}</button>
          </form>
          @endif
        </div>
        @empty
        <div class="bg-white rounded-xl p-8 text-center">
          <p class="font-medium text-[#757C98]">Kamu belum punya booking.</p>
          <a href="{{ route('front.hotels') }}" class="inline-block mt-4 font-semibold text-sm text-[#4041DA]">Cari hotel</a>
        </div>
        @endforelse
        @if ($mybookings->hasPages())
        <div class="pt-2">{{ $mybookings->links() }}</div>
        @endif
      </div>
    </div>
  </section>

@endsection