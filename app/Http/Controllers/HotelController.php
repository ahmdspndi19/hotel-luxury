<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with(['rooms'])->orderByDesc('id')->paginate(10);

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $countries = Country::orderByDesc('id')->get();
        $cities = City::orderByDesc('id')->get();

        return view('admin.hotels.create', compact('countries', 'cities'));
    }

    public function store(StoreHotelRequest $request)
    {
        $stored = [];

        try {
            DB::transaction(function () use ($request, &$stored) {
                $validated = $request->validated();
                unset($validated['photos']);

                $validated['thumbnail'] = $request->file('thumbnail')
                    ->store('thumbnails/' . date('Y/m/d'), 'public');
                $stored[] = $validated['thumbnail'];

                $validated['slug'] = Str::slug($validated['name']);

                $hotel = Hotel::create($validated);

                foreach ($request->file('photos', []) as $photo) {
                    $photoPath = $photo->store('photos/' . date('Y/m/d'), 'public');
                    $stored[] = $photoPath;
                    $hotel->photos()->create(['photo' => $photoPath]);
                }
            });
        } catch (\Throwable $e) {
            // transaksi gagal: file yang sudah terlanjur diunggah dibersihkan
            Storage::disk('public')->delete($stored);
            throw $e;
        }

        return redirect()->route('admin.hotels.index')->with('status', 'Hotel ditambahkan.');
    }

    public function show(Hotel $hotel)
    {
        $latestPhotos = $hotel->photos()->orderByDesc('id')->take(3)->get();

        return view('admin.hotels.show', compact('hotel', 'latestPhotos'));
    }

    public function edit(Hotel $hotel)
    {
        $countries = Country::orderByDesc('id')->get();
        $cities = City::orderByDesc('id')->get();
        $latestPhotos = $hotel->photos()->orderByDesc('id')->take(3)->get();

        return view('admin.hotels.edit', compact('hotel', 'countries', 'cities', 'latestPhotos'));
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $oldThumbnail = null;
        $stored = [];

        try {
            DB::transaction(function () use ($request, $hotel, &$oldThumbnail, &$stored) {
                $validated = $request->validated();
                unset($validated['photos']);

                if ($request->hasFile('thumbnail')) {
                    $oldThumbnail = $hotel->thumbnail;
                    $validated['thumbnail'] = $request->file('thumbnail')
                        ->store('thumbnails/' . date('Y/m/d'), 'public');
                    $stored[] = $validated['thumbnail'];
                } else {
                    unset($validated['thumbnail']);
                }

                $validated['slug'] = Str::slug($validated['name']);

                $hotel->update($validated);

                foreach ($request->file('photos', []) as $photo) {
                    $photoPath = $photo->store('photos/' . date('Y/m/d'), 'public');
                    $stored[] = $photoPath;
                    $hotel->photos()->create(['photo' => $photoPath]);
                }
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($stored);
            throw $e;
        }

        // thumbnail lama dibuang hanya setelah update berhasil
        if ($oldThumbnail) {
            Storage::disk('public')->delete($oldThumbnail);
        }

        return redirect()->route('admin.hotels.index')->with('status', 'Hotel diperbarui.');
    }

    public function destroy(Hotel $hotel)
    {
        if ($hotel->hasActiveBookings()) {
            return redirect()->route('admin.hotels.index')
                ->withErrors(['hotel' => 'Hotel tidak bisa dihapus: masih ada booking aktif atau mendatang.']);
        }

        DB::transaction(function () use ($hotel) {
            $hotel->delete();
        });

        return redirect()->route('admin.hotels.index')->with('status', 'Hotel dihapus.');
    }
}
