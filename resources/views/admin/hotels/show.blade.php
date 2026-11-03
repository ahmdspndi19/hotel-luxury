<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Details Hotel') }}
            </h2>
            <a href="{{ route('admin.hotels.index') }}" class="font-semibold py-2 px-6 text-gray-500 hover:text-gray-800 transition-all duration-300">
                &larr; Back to Hotels
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-flash />
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8 flex flex-col border border-gray-100">

                <!-- Header Hotel Info -->
                <div class="flex flex-row justify-between items-center pb-6">
                    <div class="flex flex-row items-center gap-x-5">
                        <img src="{{Storage::url($hotel->thumbnail)}}" alt="" class="rounded-xl object-cover w-[120px] h-[90px] shadow-sm">
                        <div class="flex flex-col">
                            <h3 class="text-indigo-950 text-2xl font-bold">
                                {{$hotel->name}}
                            </h3>
                            <p class="text-slate-500 text-sm mt-1">
                                {{$hotel->city->name}}, {{$hotel->country->name}}
                            </p>
                            <div class="mt-2">
                                <span class="text-xs font-bold py-1 px-3 rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    {{$hotel->star_level}} Star Hotel
                                </span>
                            </div>
                        </div>
                    </div> 
                    <div class="hidden md:flex flex-col text-right">
                        <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Starting Price</p>
                        <h3 class="text-indigo-950 text-xl font-bold">
                            Rp {{number_format($hotel->getLowestRoomPrice(), 0, ',', '.')}} <span class="text-sm font-normal text-gray-500">/night</span>
                        </h3>
                    </div>
                    <div class="hidden md:flex flex-row items-center gap-x-3">
                        <a href="{{route('admin.hotels.edit', $hotel)}}" class="font-semibold py-2 px-6 border border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:border-indigo-600 rounded-full transition-all duration-300">
                            Edit
                        </a>
                        <form action="{{route('admin.hotels.destroy', $hotel)}}" method="POST" class="m-0"> 
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-semibold py-2 px-6 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-full transition-all duration-300">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="border-t border-gray-100 my-2"></div>

                <!-- Gallery & Map -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-6">
                    <div class="flex flex-col gap-y-4">
                        <h3 class="text-indigo-950 text-lg font-bold">Gallery Photos</h3>
                        <div class="flex flex-row gap-4 flex-wrap"> 
                            @foreach ($latestPhotos as $photo)
                                <img src="{{Storage::url($photo->photo)}}" alt="" class="rounded-lg object-cover w-[130px] h-[95px] shadow-sm border border-gray-100">
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col gap-y-4">
                        <h3 class="text-indigo-950 text-lg font-bold">Address & Location</h3>
                        <p class="text-slate-600 text-sm">
                            {{$hotel->address}}
                        </p>
                        <div class="rounded-lg overflow-hidden border border-gray-200 shadow-sm w-full h-[200px]">
                            <iframe src="{{$hotel->link_gmaps}}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 my-2"></div>
                
                <!-- Rooms List -->
                <div class="pt-6">
                    <div class="flex flex-row justify-between items-center mb-6">
                        <h3 class="text-indigo-950 text-lg font-bold">Rooms Available</h3>
                        <a href="{{route('admin.hotel_rooms.create', $hotel->slug)}}" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300 shadow-sm">
                            + Add New Room
                        </a>
                    </div>
                    
                    <div class="flex flex-col">
                        @forelse($hotel->rooms as $room)
                        <div class="item-card flex flex-row justify-between items-center py-4 border-b border-gray-100 hover:bg-gray-50 -mx-4 px-4 transition-all duration-200">
                            <div class="flex flex-row items-center gap-x-4 w-2/5">
                                <img src="{{Storage::url($room->photo)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] shadow-sm border border-gray-100">
                                <div class="flex flex-col">
                                    <h3 class="text-indigo-950 text-lg font-bold">
                                        {{$room->name}}
                                    </h3>
                                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        {{$room->total_people}} People
                                    </p>
                                </div>
                            </div> 
                            <div class="hidden md:flex flex-col w-1/5">
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Price / Night</p>
                                <h3 class="text-indigo-950 text-base font-bold">
                                    Rp {{number_format($room->price, 0, ',', '.')}}
                                </h3>
                            </div>
                            <div class="hidden md:flex flex-row items-center justify-end gap-x-3 w-1/5">
                                <a href="{{route('admin.hotel_rooms.edit', [$hotel->slug, $room])}}" class="font-semibold py-2 px-5 border border-indigo-200 text-indigo-600 hover:bg-indigo-50 hover:border-indigo-600 rounded-full transition-all duration-300">
                                    Edit
                                </a>
                                <form action="{{route('admin.hotel_rooms.destroy', [$hotel->slug, $room])}}" method="POST" class="m-0"> 
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold py-2 px-5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-full transition-all duration-300">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty 
                            <div class="text-center py-10">
                                <p class="text-slate-500 text-lg">Belum ada kamar yang ditambahkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>