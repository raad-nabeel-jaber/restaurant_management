<?php

namespace App\Http\Controllers;

use App\Http\Concerns\ExposesDashboardNav;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ExposesDashboardNav;

    /** أقصى عدد طلبات يُعاد في JSON (آخر الطلبات حسب التاريخ) */
    public const ORDER_INDEX_DEFAULT_LIMIT = 50;

    public const ORDER_INDEX_MAX_LIMIT = 100;

    public function page(): View
    {
        $nav = $this->dashboardNav();
        $restaurant = $nav['restaurant'];

        $dashboardClient = [
            'ordersIndex' => route('orders.data'),
            'orderStatusUrl' => route('orders.update-status', ['id' => '__ORDER_ID__']),
            'checkNewOrdersUrl' => route('orders.checkNew'),
            'initialPendingCount' => $restaurant->orders()->where('status', Order::STATUS_PENDING)->count(),
            'ordersListLimit' => self::ORDER_INDEX_DEFAULT_LIMIT,
        ];

        return view('orders.index', array_merge($nav, compact('dashboardClient')));
    }

    /**
     * عدد الطلبات المعلّقة الحالي (للمطعم المرتبط بالمستخدم) — للاستطلاع من صفحة الطلبات.
     */
    public function checkNewOrders(Request $request): JsonResponse
    {
        $restaurant = auth()->user()->getOrCreateRestaurant();

        $count = $restaurant->orders()->where('status', Order::STATUS_PENDING)->count();

        return response()->json([
            'new_orders_count' => $count,
        ]);
    }

    /**
     * Return live orders for authenticated seller restaurant.
     */
    public function index(Request $request): JsonResponse
    {
        $restaurant = auth()->user()->getOrCreateRestaurant();

        $status = $request->query('status');
        $allowedStatuses = Order::STATUSES;

        $limit = (int) $request->query('limit', self::ORDER_INDEX_DEFAULT_LIMIT);
        $limit = max(10, min($limit, self::ORDER_INDEX_MAX_LIMIT));

        $ordersQuery = $restaurant->orders()
            ->with(['items.product'])
            ->latest();

        if ($status && in_array($status, $allowedStatuses, true)) {
            $ordersQuery->where('status', $status);
        }

        $totalMatching = (clone $ordersQuery)->count();
        $orders = (clone $ordersQuery)->limit($limit)->get();

        return response()->json([
            'meta' => [
                'total' => $totalMatching,
                'limit' => $limit,
                'returned' => $orders->count(),
            ],
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'customer_address' => $order->customer_address,
                    'delivery_type' => $order->delivery_type,
                    'table_number' => $order->table_number,
                    'total_price' => (float) $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at?->toDateTimeString(),
                    'items' => $order->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'product_id' => $item->product_id,
                            'product_name' => $item->product?->name,
                            'quantity' => $item->quantity,
                            'price' => (float) $item->price,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    /**
     * Update order status for authenticated seller restaurant.
     */
    public function updateStatus(UpdateOrderStatusRequest $request, string $id): JsonResponse
    {
        $validated = $request->validated();

        $restaurant = auth()->user()->getOrCreateRestaurant();
        $order = $restaurant->orders()->findOrFail($id);

        $order->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'تم تحديث حالة الطلب بنجاح.',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
            ],
        ]);
    }
}
