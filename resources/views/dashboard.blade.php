<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            
            <!-- Widget Statistik (hanya untuk admin/manager) -->
            @if ($stats)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Total Bookings</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">{{ number_format($stats['total_bookings'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Active Hotels</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">{{ number_format($stats['active_hotels'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Total Revenue</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Total Customers</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">{{ number_format($stats['total_customers'], 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.hotel_bookings.index', ['status' => 'waiting_verification']) }}" class="bg-white rounded-xl shadow-sm p-6 border border-orange-200 hover:border-orange-400 transition-all">
                    <h3 class="text-orange-600 text-xs uppercase tracking-wider font-semibold">Menunggu Verifikasi</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">{{ number_format($stats['waiting'], 0, ',', '.') }}</p>
                </a>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Booking Hari Ini</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">{{ number_format($stats['today'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-gray-500 text-xs uppercase tracking-wider font-semibold">Pendapatan Bulan Ini</h3>
                    <p class="text-3xl font-bold text-indigo-900 mt-2">Rp {{ number_format($stats['month_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>
            @endif

            <!-- Welcome Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900 font-medium text-lg">
                    👋 {{ __("Welcome back, :name!", ['name' => Auth::user()->name]) }}
                </div>
                @cannot('manage hotels')
                <div class="px-6 pb-6 flex flex-wrap gap-3">
                    <a href="{{ route('front.hotels') }}" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300">
                        Cari Hotel
                    </a>
                    @can('view hotel bookings')
                    <a href="{{ route('dashboard.my-bookings') }}" class="font-semibold py-2 px-6 text-indigo-600 border border-indigo-200 hover:bg-indigo-50 hover:border-indigo-600 rounded-full transition-all duration-300">
                        My Bookings
                    </a>
                    @endcan
                </div>
                @endcannot
            </div>
        </div>
    </div>
</x-app-layout>