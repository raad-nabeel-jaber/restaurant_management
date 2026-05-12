<?php

namespace App\View\Components;

use App\Models\Restaurant;
use Illuminate\View\Component;
use Illuminate\View\View;

class DashboardLayout extends Component
{
    public function __construct(
        public ?string $title = null,
        public ?Restaurant $restaurant = null,
        public ?string $menuUrl = null,
        public int $pendingOrdersCount = 0,
    ) {
        if (! $this->restaurant && auth()->check()) {
            $this->restaurant = auth()->user()->getOrCreateRestaurant();
            
            if (! $this->menuUrl && $this->restaurant) {
                $this->menuUrl = route('menu.show', $this->restaurant->slug);
            }
            
            if ($this->pendingOrdersCount === 0 && $this->restaurant) {
                $this->pendingOrdersCount = $this->restaurant->orders()->where('status', 'pending')->count();
            }
        }
    }

    public function render(): View
    {
        return view('layouts.dashboard');
    }
}
