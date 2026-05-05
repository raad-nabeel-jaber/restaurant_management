<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $restaurant = auth()->user()->restaurant;

        // Public menu URL for this restaurant.
        $menuUrl = route('menu.show', $restaurant->slug);

        // Generate QR code image URL using a lightweight free API.
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($menuUrl);

        return view('dashboard', compact('restaurant', 'menuUrl', 'qrCodeUrl'));
    }
}
