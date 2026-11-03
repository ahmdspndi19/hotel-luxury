<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Hotels') }}
            </h2>
            <a href="{{route('admin.hotels.create')}}" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300">
                + Add New
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-0 flex flex-col">
                <div class="p-6 flex flex-col gap-y-2">

                    @forelse ($hotels as $hotel)
                    <div class="item-card flex flex-row justify-between items-center py-4 border-b border-gray-100 hover:bg-gray-50 px-4 -mx-4 transition-all duration-200">
                        <div class="flex flex-row items-center gap-x-4 w-1/2">
                            <img src="{{Storage::url($hotel->thumbnail)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] shadow-sm">
                            <div class="flex flex-col">
                                <h3 class="text-indigo-950 text-lg font-bold">
                                    {{$hotel->name}}
                                </h3>
                                <p class="text-slate-500 text-sm mt-1 flex items-center gap-1">
                                    <!-- Ikon Pin Lokasi Opsional -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{$hotel->city->name}}, {{$hotel->country->name}}
                                </p>
                            </div>
                        </div> 
                        <div class="hidden md:flex flex-col w-1/4">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Starting Price</p>
                            <h3 class="text-indigo-950 text-base font-bold">
                                Rp {{number_format($hotel->getLowestRoomPrice(), 0, ',', '.')}} <span class="text-sm font-normal text-gray-500">/night</span>
                            </h3>
                        </div>
                        <div class="hidden md:flex flex-row items-center justify-end gap-x-3 w-1/4">
                            <a href="{{route('admin.hotels.show', $hotel)}}" class="font-semibold py-2 px-6 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-full transition-all duration-300">
                                Manage
                            </a>
                        </div>
                    </div> 
                    @empty
                    <div class="text-center py-10">
                        <p class="text-slate-500 text-lg">Belum ada data terbaru</p>
                    </div>  
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>