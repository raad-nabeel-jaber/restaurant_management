<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ExposesDashboardNav;
use App\Models\OrderItem;
use App\Models\Restaurant;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    use ExposesDashboardNav;

    public function index(): View
    {
        [
            'restaurant' => $restaurant,
            'menuUrl' => $menuUrl,
            'pendingOrdersCount' => $pendingOrdersCount,
        ] = $this->dashboardNav();

        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($menuUrl);

        $today = now()->startOfDay();
        $todayOrdersCount = $restaurant->orders()->whereDate('created_at', $today)->count();
        $acceptedOrdersCount = $restaurant->orders()->where('status', 'accepted')->count();

        $todayPaidQuery = $restaurant->orders()
            ->whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled');
        $todayRevenue = (float) (clone $todayPaidQuery)->sum('total_price');
        $todayNonCancelledCount = (clone $todayPaidQuery)->count();
        $todayAvgOrder = $todayNonCancelledCount > 0 ? $todayRevenue / $todayNonCancelledCount : 0.0;

        $productsCount = $restaurant->products()->count();

        $thisWeekStart = now()->startOfWeek();
        $lastWeekStart = $thisWeekStart->copy()->subWeek();
        $locale = app()->getLocale();

        $thisWeekEnd = $thisWeekStart->copy()->addDays(6);
        $lastWeekEnd = $lastWeekStart->copy()->addDays(6);

        $countsThisWeek = $this->orderCountsByDayInRange($restaurant, $thisWeekStart, $thisWeekEnd);
        $countsLastWeek = $this->orderCountsByDayInRange($restaurant, $lastWeekStart, $lastWeekEnd);

        $chartLabels = [];
        $chartThis = [];
        $chartLast = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $thisWeekStart->copy()->addDays($i)->locale($locale);
            $chartLabels[] = $day->translatedFormat('D');
            $key = $day->toDateString();
            $chartThis[] = (int) ($countsThisWeek[$key] ?? 0);
        }

        for ($i = 0; $i < 7; $i++) {
            $day = $lastWeekStart->copy()->addDays($i);
            $key = $day->toDateString();
            $chartLast[] = (int) ($countsLastWeek[$key] ?? 0);
        }

        $topSold = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.restaurant_id', $restaurant->id)
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->selectRaw('products.name as name, SUM(order_items.quantity) as sold')
            ->orderByDesc('sold')
            ->limit(5)
            ->get();

        $maxSold = (int) $topSold->max('sold') ?: 1;
        $palette = ['#f5a623', '#25d366', '#60a5fa', '#a78bfa', '#fb7185'];
        $topProducts = $topSold->values()->map(function ($row, $i) use ($maxSold, $palette) {
            $sold = (int) $row->sold;

            return [
                'name' => $row->name,
                'sold' => $sold,
                'pct' => (int) round(($sold / $maxSold) * 100),
                'color' => $palette[$i % count($palette)],
            ];
        })->all();

        $dashboardClient = [
            'ordersIndex' => route('orders.data'),
            'orderStatusUrl' => route('orders.update-status', ['id' => '__ORDER_ID__']),
            'chart' => [
                'labels' => $chartLabels,
                'thisWeek' => $chartThis,
                'lastWeek' => $chartLast,
            ],
            'menuUrl' => $menuUrl,
        ];

        return view('dashboard', compact(
            'restaurant',
            'menuUrl',
            'qrCodeUrl',
            'pendingOrdersCount',
            'acceptedOrdersCount',
            'todayOrdersCount',
            'todayRevenue',
            'todayAvgOrder',
            'productsCount',
            'topProducts',
            'dashboardClient',
        ));
    }

    public function barcode(): View
    {
        [
            'restaurant' => $restaurant,
            'menuUrl' => $menuUrl,
            'pendingOrdersCount' => $pendingOrdersCount,
        ] = $this->dashboardNav();

        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($menuUrl);

        $dashboardClient = [
            'ordersIndex' => route('orders.data'),
            'orderStatusUrl' => route('orders.update-status', ['id' => '__ORDER_ID__']),
            'menuUrl' => $menuUrl,
        ];

        return view('dashboard.barcode', compact(
            'restaurant',
            'menuUrl',
            'qrCodeUrl',
            'pendingOrdersCount',
            'dashboardClient',
        ));
    }

    /**
     * عدد الطلبات لكل يوم (Y-m-d) ضمن نطاق التاريخ — استعلام واحد بدل سبعة حلقات whereDate.
     *
     * @return array<string, int>
     */
    private function orderCountsByDayInRange(
        Restaurant $restaurant,
        CarbonInterface $rangeStart,
        CarbonInterface $rangeEnd,
    ): array {
        return $restaurant->orders()
            ->whereBetween('created_at', [
                Carbon::parse($rangeStart)->startOfDay(),
                Carbon::parse($rangeEnd)->endOfDay(),
            ])
            ->selectRaw('DATE(created_at) as day')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupByRaw('DATE(created_at)')
            ->pluck('aggregate', 'day')
            ->map(fn ($count) => (int) $count)
            ->all();
    }
}
