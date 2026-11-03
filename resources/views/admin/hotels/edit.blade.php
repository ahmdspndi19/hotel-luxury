<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Hotel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-8 shadow-sm sm:rounded-xl border border-gray-100"> 

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-600 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{route('admin.hotels.update', $hotel)}}" enctype="multipart/form-data" class="flex flex-col gap-y-5"> 
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="name" :value="__('Hotel Name')" class="text-slate-600 font-semibold" />
                        <x-text-input id="name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" value="{{$hotel->name}}" type="text" name="name" required autofocus autocomplete="name" />
                    </div>

                    <div>
                        <x-input-label for="thumbnail" :value="__('Thumbnail Photo')" class="text-slate-600 font-semibold mb-2" />
                        <div class="flex items-center gap-4">
                            <img src="{{Storage::url($hotel->thumbnail)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] border border-gray-200">
                            <x-text-input id="thumbnail" class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="thumbnail" autofocus autocomplete="thumbnail" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="city_id" :value="__('City')" class="text-slate-600 font-semibold" />
                            <select name="city_id" id="city_id" class="mt-2 rounded-lg w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Choose city</option>
                                @foreach ($cities as $city) 
                                <option value="{{$city->id}}" {{ $hotel->city_id == $city->id ? 'selected' : '' }}>{{$city->name}}</option>
                                @endforeach 
                            </select>
                        </div>

                        <div>
                            <x-input-label for="country_id" :value="__('Country')" class="text-slate-600 font-semibold" />
                            <select name="country_id" id="country_id" class="mt-2 rounded-lg w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Choose country</option>
                                @foreach ($countries as $country) 
                                <option value="{{$country->id}}" {{ $hotel->country_id == $country->id ? 'selected' : '' }}>{{$country->name}}</option>
                                @endforeach 
                            </select>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="link_gmaps" :value="__('Google Maps Link (Embed URL)')" class="text-slate-600 font-semibold" />
                        <x-text-input id="link_gmaps" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" value="{{$hotel->link_gmaps}}" name="link_gmaps" required autofocus autocomplete="link_gmaps" />
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Full Address')" class="text-slate-600 font-semibold" />
                        <textarea name="address" id="address" cols="30" rows="3" class="mt-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 w-full">{{$hotel->address}}</textarea>
                    </div>

                    <div>
                        <x-input-label for="star_level" :value="__('Star Level (1-5)')" class="text-slate-600 font-semibold" />
                        <x-text-input id="star_level" value="{{$hotel->star_level}}" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" min="1" max="5" name="star_level" required autofocus autocomplete="star_level" />
                    </div>

                    <div class="pt-6 mt-2 border-t border-gray-100">
                        <h3 class="text-indigo-950 font-bold mb-4">Additional Photos</h3>
                        <div class="flex flex-col gap-y-4">
                            @foreach($latestPhotos as $photo)
                            <div class="flex items-center gap-4">
                                <img src="{{Storage::url($photo->photo)}}" alt="" class="rounded-lg object-cover w-[100px] h-[75px] border border-gray-200">
                                <x-text-input class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="photos[]" autofocus />
                            </div>
                            @endforeach 
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.hotels.show', $hotel) }}" class="font-semibold py-2 px-6 text-gray-500 hover:text-gray-800 mr-4 transition-all duration-300">
                            Cancel
                        </a>
                        <button type="submit" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300 shadow-sm">
                            Update Hotel
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>