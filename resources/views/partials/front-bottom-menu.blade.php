<div id="Menu-bar" class="lg:hidden fixed bottom-[24px] left-1/2 -translate-x-1/2 px-[18px] max-w-[640px] w-full z-30">
    <div class="bg-white p-[14px_12px] rounded-full flex items-center justify-center gap-8 shadow-[0_8px_30px_0_#0A093212]">
        <a href="{{ route('front.index') }}" class="flex flex-col gap-1 items-center">
            <img src="{{ asset('assets/images/icons/' . (request()->routeIs('front.index') ? 'home-active' : 'home-nonactive') . '.svg') }}" class="w-6 h-6" alt="Home">
            <p class="text-xs leading-[18px] {{ request()->routeIs('front.index') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98]' }}">Home</p>
        </a>
        <a href="{{ route('front.hotels') }}" class="flex flex-col gap-1 items-center">
            <img src="{{ asset('assets/images/icons/' . (request()->routeIs('front.hotels*') ? 'search-active' : 'search-nonactive') . '.svg') }}" class="w-6 h-6" alt="Search">
            <p class="text-xs leading-[18px] {{ request()->routeIs('front.hotels*') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98]' }}">Search</p>
        </a>
        <a href="{{ route('dashboard.my-bookings') }}" class="flex flex-col gap-1 items-center">
            <img src="{{ asset('assets/images/icons/' . (request()->routeIs('dashboard.*') ? 'activity-active' : 'activity-nonactive') . '.svg') }}" class="w-6 h-6" alt="Activity">
            <p class="text-xs leading-[18px] {{ request()->routeIs('dashboard.*') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98]' }}">Activity</p>
        </a>
        @auth
            <a href="{{ route('profile.edit') }}" class="flex flex-col gap-1 items-center">
                <img src="{{ asset('assets/images/icons/settings-nonactive.svg') }}" class="w-6 h-6" alt="Profile">
                <p class="text-xs leading-[18px] {{ request()->routeIs('profile.*') ? 'font-semibold text-[#4041DA]' : 'font-medium text-[#757C98]' }}">Profile</p>
            </a>
        @else
            <a href="{{ route('login') }}" class="flex flex-col gap-1 items-center">
                <img src="{{ asset('assets/images/icons/settings-nonactive.svg') }}" class="w-6 h-6" alt="Login">
                <p class="text-xs leading-[18px] font-medium text-[#757C98]">Login</p>
            </a>
        @endauth
    </div>
</div>
