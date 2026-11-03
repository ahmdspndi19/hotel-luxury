@extends('layouts.front')
@section('title', $hotel->name)
@section('meta_description', 'Lihat kamar, fasilitas, ulasan, dan lokasi '.$hotel->name.'.')
@section('hide_bottom_menu', '1')
@section('front')
    <section id="content"
        class="max-w-[640px] lg:max-w-none w-full min-h-screen mx-auto flex flex-col bg-[#F8F8F8] overflow-x-hidden pb-[122px] lg:pb-16 relative">
        <div id="Top-bar"
            class="absolute z-10 top-[60px] left-[18px] lg:static lg:w-full lg:max-w-7xl lg:mx-auto lg:px-8 lg:pt-6 lg:pb-4">
            <a href="{{ route('front.hotels') }}" class="">
                <div class="w-[42px] h-[42px] flex shrink-0">
                    <img src="{{ asset('assets/images/icons/back.svg') }}" alt="icon">
                </div>
            </a>
        </div>
        <div
            class="lg:grid lg:grid-cols-[minmax(0,1fr)_360px] lg:gap-8 lg:items-start lg:max-w-7xl lg:mx-auto lg:w-full lg:px-8">
            <div id="Details-content" class="flex flex-col lg:min-w-0">
                <header class="flex flex-col lg:gap-6">
                    <div id="thumbnail-container" class="grid grid-cols-3 gap-[3px] lg:rounded-xl lg:overflow-hidden">
                        <a id="Main-thumbnail" href="{{ Storage::url($hotel->thumbnail) }}"
                            class="col-span-3 glightbox w-full aspect-[393/247] lg:aspect-[393/200] overflow-hidden"
                            data-gallery="gallery1">
                            <img src="{{ Storage::url($hotel->thumbnail) }}" class="object-cover w-full h-full"
                                alt="Foto {{ $hotel->name }}" decoding="async">
                        </a>
                        @foreach ($latestPhotos as $photo)
                            <a href="{{ Storage::url($photo->photo) }}"
                                class="glightbox w-full aspect-[129/90] lg:aspect-[129/70] overflow-hidden"
                                data-gallery="gallery1">
                                <img src="{{ Storage::url($photo->photo) }}" class="object-cover w-full h-full"
                                    alt="Galeri {{ $hotel->name }}" loading="lazy" decoding="async">
                            </a>
                        @endforeach
                    </div>
                    <div id="Hotel-info" class="bg-white p-[24px_18px] lg:p-8 lg:rounded-xl flex flex-col gap-2">
                        <div id="Hotel-rating" class="flex items-center gap-[6px]">
                            <div
                                class="badge rounded-md p-[6px_12px] gap-[2px] bg-[linear-gradient(244.6deg,_#7545FB_14.17%,_#2A3FCC_92.43%)] 
            text-[#F8F8F8] font-semibold text-sm leading-[21px]">
                                {{ $hotel->star_level }}</div>
                            <div class="ratings-container flex items-center gap-1">
                                @if ($hotel->reviews_count > 0)
                                <x-stars :rating="$hotel->reviews_avg_rating" />
                                <p class="rating font-semibold text-sm leading-[21px]">{{ number_format($hotel->reviews_avg_rating, 1) }}/5</p>
                                <p class="reviewers font-medium text-sm leading-[21px] text-[#757C98]">({{ $hotel->reviews_count }} Review)</p>
                                @else
                                <p class="reviewers font-medium text-sm leading-[21px] text-[#757C98]">Belum ada review</p>
                                @endif
                            </div>
                        </div>
                        <h1 id="Hotel-name" class="font-semibold text-2xl leading-[36px] lg:text-3xl lg:leading-[44px]">
                            {{ $hotel->name }}</h1>
                        <div id="Hotel-Location" class="flex items-center gap-1">
                            <div class="flex shrink-0 w-6 h-6">
                                <img src="{{ asset('assets/images/icons/location-grey.svg') }}" alt="icon">
                            </div>
                            <p class="text-[#757C98] font-medium text-sm leading-[21px]">{{ $hotel->city->name }},
                                {{ $hotel->country->name }}</p>
                        </div>
                    </div>
                </header>
                <div id="Facilities" class="bg-white p-[24px_18px] lg:p-8 lg:rounded-xl flex flex-col gap-3 mt-2 lg:mt-6">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-lg leading-[27px]">Popular Facilities</h2>
                        <a href="" class="font-semibold text-sm leading-[21px] text-[#4041DA]">See All</a>
                    </div>
                    <div class="card-container grid grid-cols-2 lg:grid-cols-4 gap-[18px]">
                        <a href="" class="card-facilities">
                            <div class="border border-[#DCDFE6] bg-white p-4 rounded-lg flex flex-col gap-6">
                                <div class="w-12 h-12 rounded-lg overflow-hidden flex shrink-0">
                                    <img src="{{ asset('assets/images/icons/gym.svg') }}" alt="icon">
                                </div>
                                <div class="flex flex-col gap-[2px]">
                                    <p class="font-semibold">Gym Center</p>
                                    <p class="font-medium text-xs leading-[18px] text-[#757C98]">Always stay fit.</p>
                                </div>
                            </div>
                        </a>
                        <a href="" class="card-facilities">
                            <div class="border border-[#DCDFE6] bg-white p-4 rounded-lg flex flex-col gap-6">
                                <div class="w-12 h-12 rounded-lg overflow-hidden flex shrink-0">
                                    <img src="{{ asset('assets/images/icons/free-wifi.svg') }}" alt="icon">
                                </div>
                                <div class="flex flex-col gap-[2px]">
                                    <p class="font-semibold">Free WiFi</p>
                                    <p class="font-medium text-xs leading-[18px] text-[#757C98]">WiFi with high speed.</p>
                                </div>
                            </div>
                        </a>
                        <a href="" class="card-facilities">
                            <div class="border border-[#DCDFE6] bg-white p-4 rounded-lg flex flex-col gap-6">
                                <div class="w-12 h-12 rounded-lg overflow-hidden flex shrink-0">
                                    <img src="{{ asset('assets/images/icons/room-service.svg') }}" alt="icon">
                                </div>
                                <div class="flex flex-col gap-[2px]">
                                    <p class="font-semibold">Room Services</p>
                                    <p class="font-medium text-xs leading-[18px] text-[#757C98]">Maximizing vacation.</p>
                                </div>
                            </div>
                        </a>
                        <a href="" class="card-facilities">
                            <div class="border border-[#DCDFE6] bg-white p-4 rounded-lg flex flex-col gap-6">
                                <div class="w-12 h-12 rounded-lg overflow-hidden flex shrink-0">
                                    <img src="{{ asset('assets/images/icons/swimming-pool.svg') }}" alt="icon">
                                </div>
                                <div class="flex flex-col gap-[2px]">
                                    <p class="font-semibold">Swimming Pool</p>
                                    <p class="font-medium text-xs leading-[18px] text-[#757C98]">Cool off by swimming</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="Testimonials" class="w-full overflow-hidden py-6 flex flex-col gap-3">
                    <div class="flex justify-between items-center px-[18px] lg:px-0">
                        <h2 class="font-semibold text-lg leading-[27px]">Our Happy Customer</h2>
                    </div>
                    @if ($reviews->isEmpty())
                    <p class="px-[18px] lg:px-0 font-medium text-sm leading-[21px] text-[#757C98]">Belum ada review untuk hotel ini.</p>
                    @else
                    <div class="main-carousel">
                        @foreach ($reviews as $review)
                        <div class="testimonial-card pl-[18px] lg:pl-0 lg:mr-4 last:pr-[18px] lg:last:pr-0">
                            <div class="bg-white rounded-lg p-4 flex flex-col gap-4 w-[308px] lg:w-[340px]">
                                <div class="customer flex items-center justify-between gap-[18px]">
                                    <div class="customer-info flex items-center gap-2">
                                        <div class="w-9 h-9 rounded-full bg-[#4041DA17] text-[#4041DA] font-semibold flex items-center justify-center shrink-0">{{ strtoupper(mb_substr($review->user->name, 0, 1)) }}</div>
                                        <div class="flex flex-col">
                                            <p class="font-semibold text-xs leading-[18px]">{{ $review->user->name }}</p>
                                            <p class="font-medium text-xs leading-[18px] text-[#757C98]">{{ $review->booking?->room?->name }}</p>
                                        </div>
                                    </div>
                                    <div class="ratings-container flex items-center gap-1">
                                        <x-stars :rating="$review->rating" />
                                    </div>
                                </div>
                                @if ($review->comment)
                                <p class="review-text font-semibold text-xs leading-[18px]">“{{ $review->comment }}”</p>
                                @endif
                                <hr class="border-[#DCDFE6]">
                                <p class="font-medium text-xs leading-[18px] text-[#757C98]">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div id="Procedure" class="bg-white p-[24px_18px] lg:p-8 lg:rounded-xl flex flex-col gap-3">
                    <div class="flex justify-between items-center">
                        <h2 class="font-semibold text-lg leading-[27px]">Procedure Check-In</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-[13px]">
                        <div class="flex flex-col p-3 gap-1 rounded border border-[#EEEEEE]">
                            <p class="font-medium text-xs leading-[18px] text-[#757C98]">Check-In</p>
                            <p class="font-semibold">14:00 AM</p>
                        </div>
                        <div class="flex flex-col p-3 gap-1 rounded border border-[#EEEEEE] text-right">
                            <p class="font-medium text-xs leading-[18px] text-[#757C98]">Check-Out</p>
                            <p class="font-semibold">12:00 AM</p>
                        </div>
                    </div>
                </div>
                <div id="Location" class="bg-white p-[24px_18px] lg:p-8 lg:rounded-xl flex flex-col gap-3 mt-2 lg:mt-6">
                    <div class="flex justify-between items-center">
                        <h2 class="font-semibold text-lg leading-[27px]">Location Details</h2>
                        <a id="map-link" href="" target="_blank"
                            class="font-semibold text-sm leading-[21px] text-[#4041DA]">See Map</a>
                    </div>
                    <div id="embed-map-display" class="w-full aspect-[357/180] lg:aspect-[357/130] overflow-hidden">
                        <iframe id="map-iframe" class="w-full h-full z-0" frameborder="0"
                            src="https://www.google.com/maps/embed/v1/place?q={{ $hotel->name }}&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
                    </div>
                    <p class="font-medium text-xs leading-[18px] text-[#757C98]">{{ $hotel->address }}</p>
                </div>
            </div>
            <div id="Price-bar"
                class="fixed bottom-[24px] px-[18px] max-w-[640px] w-full z-30 lg:sticky lg:top-6 lg:bottom-auto lg:px-0 lg:max-w-none lg:self-start">
                <div
                    class="bg-white p-4 pl-6 rounded-full flex items-center justify-between shadow-[0_8px_30px_0_#0A093212] lg:flex-col lg:items-stretch lg:gap-5 lg:rounded-xl lg:p-6">
                    <div class="price-info flex flex-col gap-[2px]">
                        <p class="font-semibold text-lg leading-[27px] text-[#54A917]">Rp
                            {{ number_format($hotel->getLowestRoomPrice(), 0, ',', '.') }}</p>
                        <p class="font-semibold text-xs leading-[18px] text-[#757C98]">/night</p>
                    </div>
                    <a href="{{ route('front.hotels.rooms', $hotel->slug) }}"
                        class="w-[138px] lg:w-full h-[48px] bg-[#4041DA] p-[12px_24px] rounded-full text-nowrap text-white font-semibold text-sm leading-[21px] flex items-center justify-center">Select
                        Room</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-styles')
    <link rel="stylesheet" href="https://unpkg.com/flickity@2.3.0/dist/flickity.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css" />
@endpush

@push('after-scripts')
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/flickity@2.3.0/dist/flickity.pkgd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js"></script>

    <script src="{{ asset('js/hotel-details.js') }}"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
    <script src="{{ asset('js/map.js') }}"></script>
@endpush
