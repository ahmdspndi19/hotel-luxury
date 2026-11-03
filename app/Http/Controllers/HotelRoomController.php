<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HotelRoomController extends Controller
{
    public function create(Hotel $hotel)
    {
        return view('admin.hotel_rooms.create', compact('hotel'));
    }

    public function store(StoreRoomRequest $request, Hotel $hotel)
    {
        $photoPath = null;

        try {
            DB::transaction(function () use ($request, $hotel, &$photoPath) {
                $validated = $request->validated();

                $photoPath = $request->file('photo')->store('photos/' . date('Y/m/d'), 'public');
                $validated['photo'] = $photoPath;
                $validated['hotel_id'] = $hotel->id;

                HotelRoom::create($validated);
            });
        } catch (\Throwable $e) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            throw $e;
        }

        return redirect()->route('admin.hotels.show', $hotel->id)->with('status', 'Tipe kamar ditambahkan.');
    }

    public function edit(Hotel $hotel, HotelRoom $hotelRoom)
    {
        // kamar harus milik hotel di URL
        abort_if($hotelRoom->hotel_id !== $hotel->id, 404);

        return view('admin.hotel_rooms.edit', compact('hotelRoom', 'hotel'));
    }

    public function update(UpdateRoomRequest $request, HotelRoom $hotelRoom)
    {
        $oldPhoto = null;
        $newPhoto = null;

        try {
            DB::transaction(function () use ($request, $hotelRoom, &$oldPhoto, &$newPhoto) {
                $validated = $request->validated();

                if ($request->hasFile('photo')) {
                    $oldPhoto = $hotelRoom->photo;
                    $newPhoto = $request->file('photo')->store('photos/' . date('Y/m/d'), 'public');
                    $validated['photo'] = $newPhoto;
                } else {
                    unset($validated['photo']);
                }

                $hotelRoom->update($validated);
            });
        } catch (\Throwable $e) {
            if ($newPhoto) {
                Storage::disk('public')->delete($newPhoto);
            }
            throw $e;
        }

        if ($oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('admin.hotels.show', $hotelRoom->hotel_id)->with('status', 'Tipe kamar diperbarui.');
    }

    public function destroy(Hotel $hotel, HotelRoom $hotelRoom)
    {
        abort_if($hotelRoom->hotel_id !== $hotel->id, 404);

        if ($hotelRoom->hasActiveBookings()) {
            return redirect()->route('admin.hotels.show', $hotel->id)
                ->withErrors(['room' => 'Tipe kamar tidak bisa dihapus: masih ada booking aktif atau mendatang.']);
        }

        DB::transaction(function () use ($hotelRoom) {
            $hotelRoom->delete();
        });

        return redirect()->route('admin.hotels.show', $hotel->id)->with('status', 'Tipe kamar dihapus.');
    }
}
