<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Country') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden p-8 shadow-sm sm:rounded-xl border border-gray-100">  
                <form method="POST" action="{{route('admin.countries.store')}}" enctype="multipart/form-data"> 
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Name')" class="text-slate-600 font-semibold" />
                        <x-text-input id="name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <a href="{{ route('admin.countries.index') }}" class="font-semibold py-2 px-6 text-gray-500 hover:text-gray-800 mr-4 transition-all duration-300">
                            Cancel
                        </a>
                        <button type="submit" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300 shadow-sm">
                            Add New Country
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>