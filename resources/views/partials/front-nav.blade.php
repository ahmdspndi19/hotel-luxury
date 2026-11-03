<header class="hidden lg:block sticky top-0 z-40 bg-white border-b border-[#EEEEF4]">
    <nav class="max-w-7xl mx-auto px-8 h-[72px] flex items-center justify-between">
        <a href="{{ route('front.index') }}" class="font-bold text-xl text-[#4041DA]">Luxury</a>
        <div class="flex items-center gap-8">
            <a href="{{ route('front.index') }}" class="text-sm {{ request()->routeIs('front.index') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98] hover:text-[#4041DA]' }}">Home</a>
            <a href="{{ route('front.hotels') }}" class="text-sm {{ request()->routeIs('front.hotels*') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98] hover:text-[#4041DA]' }}">Search</a>
            @auth
                <a href="{{ route('dashboard.my-bookings') }}" class="text-sm {{ request()->routeIs('dashboard.my-bookings') || request()->routeIs('dashboard.booking_details') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98] hover:text-[#4041DA]' }}">My Bookings</a>
                <a href="{{ route('profile.edit') }}" class="text-sm {{ request()->routeIs('profile.*') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98] hover:text-[#4041DA]' }}">{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-[#F93F6C]">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-[#757C98] hover:text-[#4041DA]">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-[#4041DA] rounded-full px-5 py-2">Register</a>
                @endif
            @endauth
        </div>
    </nav>
</header>
