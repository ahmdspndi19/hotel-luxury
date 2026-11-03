@extends('layouts.front')
@section('title', 'My Profile')
@section('robots', 'noindex,nofollow')
@section('front')

<section id="content" class="max-w-[640px] lg:max-w-none w-full min-h-screen mx-auto flex flex-col bg-[#F8F8F8] overflow-x-hidden pb-6 lg:pb-16 relative">
    <div class="w-full h-[165px] lg:h-[200px] absolute top-0 bg-[linear-gradient(244.6deg,_#7545FB_14.17%,_#2A3FCC_92.43%)]"></div>
    <div class="relative z-10 px-[18px] lg:px-8 w-full lg:max-w-3xl lg:mx-auto flex flex-col gap-6 mt-[60px] lg:mt-10">
        <div class="top-menu flex justify-between items-center">
            <a href="{{ route('front.index') }}">
                <div class="w-[42px] h-[42px] flex shrink-0">
                    <img src="{{ asset('assets/images/icons/back.svg') }}" alt="Kembali">
                </div>
            </a>
            <p class="font-semibold text-lg leading-[28px] text-white text-center">My Profile</p>
            <div class="dummy-spacer w-[42px] h-[42px] flex shrink-0"></div>
        </div>

        <div class="bg-white rounded-xl p-4 lg:p-6 flex items-center gap-4">
            <div class="w-[72px] h-[72px] flex shrink-0 rounded-full overflow-hidden bg-gray-200">
                <img src="{{ Storage::url($user->avatar) }}" class="w-full h-full object-cover" alt="Avatar">
            </div>
            <div class="flex flex-col gap-1 min-w-0">
                <p class="font-semibold text-lg truncate">{{ $user->name }}</p>
                <p class="font-medium text-sm text-[#757C98] truncate">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('dashboard.my-bookings') }}" class="flex items-center justify-center font-semibold p-[12px_24px] rounded-lg h-12 bg-[#4041DA] text-white">My Bookings</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center font-semibold p-[12px_24px] rounded-lg h-12 bg-[#F93F6C17] text-[#F93F6C]">Logout</button>
            </form>
        </div>

        <div class="p-4 lg:p-6 bg-white rounded-xl">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="p-4 lg:p-6 bg-white rounded-xl">
            @include('profile.partials.update-password-form')
        </div>

        <div class="p-4 lg:p-6 bg-white rounded-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</section>

@endsection
