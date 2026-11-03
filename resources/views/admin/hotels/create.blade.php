<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Hotel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-8 shadow-sm sm:rounded-xl border border-gray-100"> 
                <form method="POST" action="{{route('admin.hotels.store')}}" enctype="multipart/form-data" class="flex flex-col gap-y-5"> 
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Hotel Name')" class="text-slate-600 font-semibold" />
                        <x-text-input id="name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="thumbnail" :value="__('Thumbnail Photo')" class="text-slate-600 font-semibold" />
                        <x-text-input id="thumbnail" class="block mt-2 w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="thumbnail" required autofocus autocomplete="thumbnail" />
                        <x-input-error :messages="$errors->get('thumbnail')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="city_id" :value="__('City')" class="text-slate-600 font-semibold" />
                            <select name="city_id" id="city_id" class="mt-2 rounded-lg w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Choose city</option>
                                @foreach ($cities as $city) 
                                <option value="{{$city->id}}">{{$city->name}}</option>
                                @endforeach 
                            </select>
                            <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="country_id" :value="__('Country')" class="text-slate-600 font-semibold" />
                            <select name="country_id" id="country_id" class="mt-2 rounded-lg w-full border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Choose country</option>
                                @foreach ($countries as $country) 
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach 
                            </select>
                            <x-input-error :messages="$errors->get('country_id')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="link_gmaps" :value="__('Google Maps Link (Embed URL)')" class="text-slate-600 font-semibold" />
                        <x-text-input id="link_gmaps" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="link_gmaps" :value="old('link_gmaps')" required autofocus autocomplete="link_gmaps" placeholder="https://www.google.com/maps/embed?..." />
                        <x-input-error :messages="$errors->get('link_gmaps')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="address" :value="__('Full Address')" class="text-slate-600 font-semibold" />
                        <textarea name="address" id="address" cols="30" rows="3" class="mt-2 border border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 w-full"></textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="star_level" :value="__('Star Level (1-5)')" class="text-slate-600 font-semibold" />
                        <x-text-input id="star_level" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="number" min="1" max="5" name="star_level" :value="old('star_level')" required autofocus autocomplete="star_level" />
                        <x-input-error :messages="$errors->get('star_level')" class="mt-2" />
                    </div>

                    <div class="pt-6 mt-2 border-t border-gray-100">
                        <h3 class="text-indigo-950 font-bold mb-4">Additional Photos</h3>
                        <div class="flex flex-col gap-y-4">
                            @for($i = 0; $i < 3; $i++)
                                <div>
                                    <x-text-input id="photo_{{$i}}" class="block w-full text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" type="file" name="photos[]" required autofocus />
                                </div> 
                            @endfor
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.hotels.index') }}" class="font-semibold py-2 px-6 text-gray-500 hover:text-gray-800 mr-4 transition-all duration-300">
                            Cancel
                        </a>
                        <button type="submit" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300 shadow-sm">
                            Add New Hotel
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>