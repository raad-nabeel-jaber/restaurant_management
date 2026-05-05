<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    private function findRestaurantByBarcode(string $barcode): ?Restaurant
    {
        $restaurantQuery = Restaurant::query()->where('is_active', true);

        if (is_numeric($barcode)) {
            $restaurantQuery->where('id', (int) $barcode);
        } else {
            $restaurantQuery->where('slug', $barcode);
        }

        return $restaurantQuery->first();
    }

    private function fullMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url(Storage::url($path));
    }

    /**
     * Return customer-facing menu data based on scanned barcode value.
     */
    public function show(string $barcode): JsonResponse
    {
        $restaurant = $this->findRestaurantByBarcode($barcode)?->load([
            'categories' => function ($query) {
                $query->orderBy('sort_order')
                    ->with([
                        'products' => function ($productsQuery) {
                            $productsQuery
                                ->where('is_available', true)
                                ->orderBy('name');
                        },
                    ]);
            },
        ]);

        if (! $restaurant) {
            return response()->json([
                'message' => 'المطعم غير موجود أو غير مفعل.',
            ], 404);
        }

        return response()->json([
            'restaurant' => [
                'id' => $restaurant->id,
                'name' => $restaurant->name,
                'slug' => $restaurant->slug,
                'logo' => $this->fullMediaUrl($restaurant->logo),
                'whatsapp_number' => $restaurant->whatsapp_number,
            ],
            'categories' => $restaurant->categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'sort_order' => $category->sort_order,
                    'products' => $category->products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'price' => $product->price,
                            'image' => $this->fullMediaUrl($product->image),
                            'is_available' => $product->is_available,
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    /**
     * Store a customer order and return WhatsApp URL.
     */
    public function storeOrder(StoreMenuOrderRequest $request, string $barcode): JsonResponse
    {
        $restaurant = $this->findRestaurantByBarcode($barcode);

        if (! $restaurant) {
            return response()->json([
                'message' => 'المطعم غير موجود أو غير مفعل.',
            ], 404);
        }

        $validated = $request->validated();

        $productIds = collect($validated['items'])->pluck('product_id')->unique()->values();

        $products = Product::query()
            ->where('restaurant_id', $restaurant->id)
            ->where('is_available', true)
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        if ($products->count() !== $productIds->count()) {
            return response()->json([
                'message' => 'بعض المنتجات غير متاحة أو لا تتبع هذا المطعم.',
            ], 422);
        }

        $normalizedItems = collect($validated['items'])
            ->groupBy('product_id')
            ->map(function ($groupedItems, $productId) use ($products) {
                $product = $products->get((int) $productId);
                $quantity = collect($groupedItems)->sum('quantity');
                $lineTotal = $quantity * (float) $product->price;

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => (float) $product->price,
                    'line_total' => $lineTotal,
                ];
            })
            ->values();

        $totalPrice = $normalizedItems->sum('line_total');

        $order = DB::transaction(function () use ($restaurant, $validated, $normalizedItems, $totalPrice) {
            $order = Order::create([
                'restaurant_id' => $restaurant->id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_type' => $validated['delivery_type'],
                'table_number' => $validated['delivery_type'] === 'dine_in' ? ($validated['table_number'] ?? null) : null,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            $order->items()->createMany(
                $normalizedItems->map(function ($item) {
                    return [
                        'product_id' => $item['product']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ];
                })->all()
            );

            return $order->load('items.product');
        });

        $messageLines = [
            "طلب جديد #{$order->id}",
            "الاسم: {$order->customer_name}",
            "رقم العميل: {$order->customer_phone}",
            'نوع الطلب: '.($order->delivery_type === 'delivery' ? 'توصيل' : 'داخل المطعم'),
        ];

        if ($order->delivery_type === 'dine_in' && $order->table_number) {
            $messageLines[] = "رقم الطاولة: {$order->table_number}";
        }

        $messageLines[] = '---';
        $messageLines[] = 'تفاصيل الطلب:';

        foreach ($order->items as $item) {
            $lineTotal = (float) $item->price * $item->quantity;
            $messageLines[] = "- {$item->product->name} x{$item->quantity} = ".number_format($lineTotal, 2);
        }

        $messageLines[] = '---';
        $messageLines[] = 'الإجمالي: '.number_format((float) $order->total_price, 2);

        $restaurantPhone = preg_replace('/\D+/', '', $restaurant->whatsapp_number ?? '');
        $whatsAppUrl = "https://wa.me/{$restaurantPhone}?text=".rawurlencode(implode("\n", $messageLines));

        return response()->json([
            'message' => 'تم استلام الطلب بنجاح.',
            'order_id' => $order->id,
            'total_price' => (float) $order->total_price,
            'whatsapp_url' => $whatsAppUrl,
        ], 201);
    }
}
