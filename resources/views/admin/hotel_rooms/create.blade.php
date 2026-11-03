<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Hotel Room') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-8 shadow-sm sm:rounded-xl border border-gray-100">

                <div class="flex flex-row items-center gap-x-4 pb-6 border-b border-gray-100 mb-6">
                    <img src="{{Storage::url($hotel->thumbnail)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] shadow-sm">
                    <div class="flex flex-col">
                        <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Adding room for</p>
                        <h3 class="text-indigo-950 text-lg font-bold">
                            {{$hotel->name}}
                        </h3>
                    </div>
                </div> 

                <form method="POST" action="{{route('admin.hotel_rooms.store', $hotel->slug)}}" enctype="multipart/form-data" class="flex flex-col gap-y-5"> 
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Room Name')" class="text-slate-600 font-semibold" />
                        <x-text-input id="name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="photo" :value="__('Room Photo')" class="text-slate-600 font-semibold" />
                        <x-text-input id="photo" class="block mt-2 w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="photo" required autofocus autocomplete="photo" />
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="price" :value="__('Price per Night (Rp)')" class="text-slate-600 font-semibold" />
                            <x-text-input id="price" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" name="price" :value="old('price')" required autofocus autocomplete="price" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="total_people" :value="__('Capacity (People)')" class="text-slate-600 font-semibold" />
                            <x-text-input id="total_people" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" name="total_people" :value="old('total_people')" required autofocus autocomplete="total_people" />
                            <x-input-error :messages="$errors->get('total_people')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="total_rooms" :value="__('Total Rooms (units)')" class="text-slate-600 font-semibold" />
                        <x-text-input id="total_rooms" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" min="1" name="total_rooms" :value="old('total_rooms', 1)" required />
                        <x-input-error :messages="$errors->get('total_rooms')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.hotels.show', $hotel) }}" class="font-semibold py-2 px-6 text-gray-500 hover:text-gray-800 mr-4 transition-all duration-300">
                            Cancel
                        </a>
                        <button type="submit" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300 shadow-sm">
                            Add New Room
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>