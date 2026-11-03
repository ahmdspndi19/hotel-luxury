<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Cities') }}
            </h2>
            <a href="{{route('admin.cities.create')}}" class="font-semibold py-2 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full transition-all duration-300">
                + Add New
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-0 flex flex-col">
                <div class="p-6 flex flex-col gap-y-2">
                    
                    @forelse ($cities as $city)
                    <div class="item-card flex flex-row justify-between items-center py-4 border-b border-gray-100 hover:bg-gray-50 px-4 -mx-4 transition-all duration-200">
                        <div class="flex flex-row items-center gap-x-3 w-1/3">
                            <div class="flex flex-col">
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Name</p>
                                <h3 class="text-indigo-950 text-lg font-bold">
                                    {{$city->name}}
                               </h3>
                            </div>
                        </div> 
                        <div class="hidden md:flex flex-col w-1/3">
                            <p class="text-slate-500 text-xs uppercase tracking-wider font-semibold mb-1">Date Added</p>
                            <h3 class="text-slate-700 text-base font-semibold">
                                {{$city->created_at->format('M d, Y')}}
                            </h3>
                        </div>
                        <div class="hidden md:flex flex-row items-center justify-end gap-x-3 w-1/3">
                            <a href="{{route('admin.cities.edit', $city)}}" class="font-semibold py-2 px-5 text-indigo-600 border border-indigo-200 hover:bg-indigo-50 hover:border-indigo-600 rounded-full transition-all duration-300">
                                Edit
                            </a>
                            <form action="{{route('admin.cities.destroy', $city)}}" method="POST" class="m-0"> 
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
                        <p class="text-slate-500 text-lg">Belum ada data terbaru</p>
                    </div>
                    @endforelse

                    <div class="mt-4">
                        {{$cities->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>