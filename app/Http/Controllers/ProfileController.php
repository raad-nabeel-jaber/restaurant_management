<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ExposesDashboardNav;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use ExposesDashboardNav;

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $nav = $this->dashboardNav();

        return view('profile.edit', array_merge($nav, [
            'user' => $request->user(),
        ]));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = $request->user();
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $restaurant = $user->getOrCreateRestaurant();
        $slug = $validated['slug'] !== '' ? $validated['slug'] : $restaurant->slug;

        $restaurant->fill([
            'name' => $validated['restaurant_name'],
            'slug' => $slug,
            'whatsapp_number' => $validated['whatsapp_number'] ?? '',
            'is_active' => $request->boolean('is_active'),
            'order_method' => $validated['order_method'],
        ]);

        if ($request->exists('whatsapp_orders_enabled')) {
            $restaurant->whatsapp_orders_enabled = $request->boolean('whatsapp_orders_enabled');
        }

        if ($request->hasFile('logo')) {
            if ($restaurant->logo && Storage::disk('public')->exists($restaurant->logo)) {
                Storage::disk('public')->delete($restaurant->logo);
            }
            $restaurant->logo = $request->file('logo')->store('restaurants', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($restaurant->cover_image && Storage::disk('public')->exists($restaurant->cover_image)) {
                Storage::disk('public')->delete($restaurant->cover_image);
            }
            $restaurant->cover_image = $request->file('cover_image')->store('restaurants', 'public');
        }

        $restaurant->save();

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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
