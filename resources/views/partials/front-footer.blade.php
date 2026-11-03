<footer class="bg-white border-t border-[#EEEEF4] pb-[130px] lg:pb-0">
    <div class="max-w-[640px] lg:max-w-7xl mx-auto px-[18px] lg:px-8 py-8 lg:py-12 flex flex-col lg:flex-row lg:justify-between gap-8">
        <div class="flex flex-col gap-2 lg:max-w-sm">
            <p class="font-bold text-lg text-[#4041DA]">Luxury</p>
            <p class="font-medium text-sm leading-[21px] text-[#757C98]">Cari kamar, cek ketersediaan, dan pesan hotel dalam beberapa langkah.</p>
        </div>
        <div class="grid grid-cols-2 gap-8 lg:gap-16">
            <div class="flex flex-col gap-3">
                <p class="font-semibold text-sm">Menu</p>
                <a href="{{ route('front.index') }}" class="font-medium text-sm text-[#757C98] hover:text-[#4041DA]">Home</a>
                <a href="{{ route('front.hotels') }}" class="font-medium text-sm text-[#757C98] hover:text-[#4041DA]">Cari Hotel</a>
            </div>
            <div class="flex flex-col gap-3">
                <p class="font-semibold text-sm">Akun</p>
                @auth
                    <a href="{{ route('dashboard.my-bookings') }}" class="font-medium text-sm text-[#757C98] hover:text-[#4041DA]">My Bookings</a>
                    <a href="{{ route('profile.edit') }}" class="font-medium text-sm text-[#757C98] hover:text-[#4041DA]">Profil</a>
                @else
                    <a href="{{ route('login') }}" class="font-medium text-sm text-[#757C98] hover:text-[#4041DA]">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-medium text-sm text-[#757C98] hover:text-[#4041DA]">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    <div class="border-t border-[#EEEEF4] py-4 text-center">
        <p class="font-medium text-xs text-[#757C98]">&copy; {{ date('Y') }} Luxury</p>
    </div>
</footer>
