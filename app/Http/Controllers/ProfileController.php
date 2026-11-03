<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\HotelBooking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $isAdmin = $user->canany(['manage cities', 'manage countries', 'manage hotels', 'manage hotel bookings']);

        return view($isAdmin ? 'profile.edit' : 'profile.customer', [
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->except('avatar'));

        $oldAvatar = null;

        if ($request->hasFile('avatar')) {
            $oldAvatar = $user->avatar;
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($oldAvatar && str_starts_with($oldAvatar, 'avatars/')) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // booking yang masih menahan kamar / uang tamu tidak boleh ikut terhapus bersama akun
        $hasActive = HotelBooking::where('user_id', $user->id)
            ->blocking()
            ->where('checkout_at', '>=', now()->toDateString())
            ->exists();

        if ($hasActive) {
            return Redirect::route('profile.edit')->withErrors(
                ['password' => 'Akun tidak bisa dihapus: masih ada booking aktif. Batalkan atau tunggu sampai check-out.'],
                'userDeletion'
            );
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
