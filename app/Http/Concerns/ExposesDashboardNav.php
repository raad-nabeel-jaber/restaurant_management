<?php

namespace App\Http\Concerns;

trait ExposesDashboardNav
{
    /**
     * @return array{restaurant: \App\Models\Restaurant, menuUrl: string, pendingOrdersCount: int}
     */
    protected function dashboardNav(): array
    {
        $restaurant = auth()->user()->getOrCreateRestaurant();

        return [
            'restaurant' => $restaurant,
            'menuUrl' => route('menu.show', $restaurant->slug),
            'pendingOrdersCount' => $restaurant->orders()->where('status', 'pending')->count(),
        ];
    }
}
