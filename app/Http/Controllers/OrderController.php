<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Return live orders for authenticated seller restaurant.
     */
    public function index(Request $request): JsonResponse
    {
        $restaurant = auth()->user()->restaurant;

        $status = $request->query('status');
        $allowedStatuses = ['pending', 'accepted', 'cancelled'];

        $ordersQuery = $restaurant->orders()
            ->with(['items.product'])
            ->latest();

        if ($status && in_array($status, $allowedStatuses, true)) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->get();

        return response()->json([
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
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

        $restaurant = auth()->user()->restaurant;
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
