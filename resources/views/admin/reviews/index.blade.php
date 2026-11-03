<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Reviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-0 flex flex-col">
                @if (session('status'))
                    <div class="mx-6 mt-6 rounded-lg bg-green-50 text-green-700 text-sm font-medium p-3">{{ session('status') }}</div>
                @endif
                <div class="p-6 flex flex-col gap-y-2">
                    @forelse ($reviews as $review)
                        <div class="flex flex-row justify-between items-start gap-6 py-4 border-b border-gray-100">
                            <div class="flex flex-col gap-1 w-1/4">
                                <h3 class="text-indigo-950 text-base font-bold">{{ $review->hotel->name }}</h3>
                                <p class="text-slate-500 text-sm">{{ $review->user->name }}</p>
                                <p class="text-slate-400 text-xs">{{ $review->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="flex flex-col gap-2 flex-1">
                                <x-stars :rating="$review->rating" />
                                <p class="text-sm text-slate-700">{{ $review->comment ?: 'Tanpa komentar.' }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-xs font-bold py-1 px-3 rounded-full text-white" style="background: {{ $review->is_hidden ? '#757C98' : '#54A917' }}">
                                    {{ $review->is_hidden ? 'HIDDEN' : 'VISIBLE' }}
                                </span>
                                <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="font-semibold py-2 px-6 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-full transition-all duration-300">
                                        {{ $review->is_hidden ? 'Show' : 'Hide' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-slate-500 text-lg">Belum ada review</p>
                        </div>
                    @endforelse

                    <div class="mt-4">{{ $reviews->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
