@extends('layouts.front')
@section('title', 'Find Your Perfect Stay')
@section('front')
    <section id="content"
        class="max-w-[640px] lg:max-w-none w-full min-h-screen mx-auto flex flex-col bg-[#F8F8F8] overflow-x-hidden pb-6 lg:pb-16 relative">
        <header
            class="flex justify-center h-[376px] lg:h-[460px] px-[18px] relative overflow-hidden -mb-[106px] lg:-mb-[90px]">
            <!-- Menggunakan helper asset() untuk background -->
            <img src="{{ asset('assets/images/backgrounds/BG.png') }}" class="absolute object-cover w-full h-full"
                alt="backgrounds">
            <div class="fixed top-0 w-full max-w-[640px] px-[18px] z-30 lg:hidden">
                <nav
                    class="bg-white p-[10px_16px] h-fit w-full flex items-center justify-between rounded-full shadow-[0_8px_30px_0_#0A093212] z-10 mt-[60px] lg:mt-6">

                    <!-- Pemisahan link profil berdasarkan status login -->
                    @guest
                        <a href="{{ route('login') }}"> <!-- Arahkan ke halaman login -->
                            <div
                                class="w-[54px] h-[54px] flex shrink-0 overflow-hidden rounded-full items-center justify-center">
                                <img src="{{ asset('assets/images/icons/user.svg') }}" class="w-full h-full object-cover"
                                    alt="icon">
                            </div>
                        </a>
                    @endguest
                    @auth
                        <a href="{{ route('profile.edit') }}"> <!-- Arahkan ke dashboard jika sudah login -->
                            <div
                                class="w-[54px] h-[54px] flex shrink-0 overflow-hidden rounded-full items-center justify-center">
                                <img src="{{ Storage::url(Auth::user()->avatar) }}" class="w-full h-full object-cover"
                                    alt="icon">
                            </div>
                        </a>
                    @endauth

                    <div class="flex flex-col gap-[2px] text-center lg:text-left lg:ml-4">
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Current location</p>
                        <div class="flex items-center justify-between gap-1">
                            <div class="flex shrink-0">
                                <img src="{{ asset('assets/images/icons/location.svg') }}" alt="icon">
                                <p class="font-semibold text-sm leading-[21px]">Wakanda, West Java</p>
                            </div>
                        </div>
                    </div>
                    <a href="">
                        <div
                            class="w-[54px] h-[54px] flex shrink-0 overflow-hidden rounded-full items-center justify-center">
                            <img src="{{ asset('assets/images/icons/Notifcations.svg') }}" alt="icon">
                        </div>
                    </a>
                </nav>
            </div>
            <!-- Teks hero khusus desktop -->
            <div class="hidden lg:flex flex-col items-center justify-center text-center gap-3 relative z-10 pb-24 pt-16">
                <h1 class="font-bold text-4xl leading-[54px] text-white">Temukan Hotel Impianmu</h1>
                <p class="font-medium text-base text-white/80">Pesan kamar terbaik dengan harga terbaik, kapan saja dan di
                    mana saja.</p>
            </div>
        </header>
        <div id="Feature" class="px-[18px] lg:px-8 w-full lg:max-w-7xl lg:mx-auto relative z-10">
            <div
                class="bg-white p-[18px_16px] lg:p-8 rounded-xl lg:shadow-[0_8px_30px_0_#0A093212] overflow-hidden grid grid-cols-4 lg:grid-cols-8 gap-[27px] lg:gap-6">
                <a href="{{ route('front.hotels') }}">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Hotel.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Hotel</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Flight.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Flight</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Train.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Train</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Bus.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Bus</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Ferry.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Ferry</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Finance.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Finance</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/Travel.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">Travel</p>
                    </div>
                </a>
                <a href="#">
                    <div class="flex flex-col items-center gap-2">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/View All.png') }}" class="object-cover" alt="icon">
                        </div>
                        <p class="font-medium text-sm text-[#757C98] leading-[21px]">View All</p>
                    </div>
                </a>
            </div>
        </div>
        <div id="Promo" class="mt-6 lg:mt-10 w-full bg-white overflow-hidden py-6 flex flex-col gap-3">
            <div class="flex justify-between items-center px-[18px] lg:px-8 w-full lg:max-w-7xl lg:mx-auto">
                <h1 class="font-semibold text-lg leading-[27px]">Promo Special For You✨</h1>
                <a href="" class="font-semibold text-sm leading-[21px] text-[#4041DA]">See All</a>
            </div>
            <div class="main-carousel w-full lg:max-w-7xl lg:mx-auto lg:px-8">
                <a href="" class="pl-[18px] last:pr-[18px]">
                    <div class="w-fit flex flex-col gap-4">
                        <div
                            class="w-[310px] h-[160px] lg:w-[380px] lg:h-[200px] flex shrink-0 overflow-hidden rounded-xl">
                            <img src="{{ asset('assets/images/thumbnails/thumbnail1.png') }}"
                                class="object-cover w-full h-full" alt="thumbnail">
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="font-semibold">Wonderful Indonesia</p>
                            <p class="text-[#757C98] font-semibold text-sm leading-[21px]">Available 9 Promos</p>
                        </div>
                    </div>
                </a>
                <a href="" class="pl-[18px] last:pr-[18px]">
                    <div class="w-fit flex flex-col gap-4">
                        <div
                            class="w-[310px] h-[160px] lg:w-[380px] lg:h-[200px] flex shrink-0 overflow-hidden rounded-xl">
                            <img src="{{ asset('assets/images/thumbnails/thumbnail2.png') }}"
                                class="object-cover w-full h-full" alt="thumbnail">
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="font-semibold">Majestic Thailand</p>
                            <p class="text-[#757C98] font-semibold text-sm leading-[21px]">Available 25 Promos</p>
                        </div>
                    </div>
                </a>
                <a href="" class="pl-[18px] last:pr-[18px]">
                    <div class="w-fit flex flex-col gap-4">
                        <div
                            class="w-[310px] h-[160px] lg:w-[380px] lg:h-[200px] flex shrink-0 overflow-hidden rounded-xl">
                            <img src="{{ asset('assets/images/thumbnails/thumbnail3.png') }}"
                                class="object-cover w-full h-full" alt="thumbnail">
                        </div>
                        <div class="flex flex-col gap-1">
                            <p class="font-semibold">Amazing Singapore</p>
                            <p class="text-[#757C98] font-semibold text-sm leading-[21px]">Available 22 Promos</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('after-styles')
    <link rel="stylesheet" href="https://unpkg.com/flickity@2.3.0/dist/flickity.min.css">
@endpush

@push('after-scripts')
    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/flickity@2.3.0/dist/flickity.pkgd.min.js"></script>

    <script src="{{ asset('js/carousel.js') }}"></script>
@endpush