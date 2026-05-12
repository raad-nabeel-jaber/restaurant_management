<?php

namespace Tests\Feature;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_json_is_limited_and_returns_meta_total(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Orders Limit Restaurant',
            'slug' => 'orders-limit-rest',
            'whatsapp_number' => '966501234567',
            'is_active' => true,
        ]);

        for ($i = 0; $i < 55; $i++) {
            Order::create([
                'restaurant_id' => $restaurant->id,
                'customer_name' => 'عميل '.$i,
                'customer_phone' => '0790000000',
                'delivery_type' => 'delivery',
                'table_number' => null,
                'total_price' => 10.00,
                'status' => 'pending',
            ]);
        }

        $response = $this->actingAs($user)->getJson(route('orders.data'));

        $response
            ->assertOk()
            ->assertJsonPath('meta.total', 55)
            ->assertJsonPath('meta.limit', OrderController::ORDER_INDEX_DEFAULT_LIMIT)
            ->assertJsonCount(OrderController::ORDER_INDEX_DEFAULT_LIMIT, 'orders');
    }

    public function test_orders_data_respects_status_filter_and_limit(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Filter Restaurant',
            'slug' => 'orders-filter-rest',
            'whatsapp_number' => '966501234567',
            'is_active' => true,
        ]);

        for ($i = 0; $i < 12; $i++) {
            Order::create([
                'restaurant_id' => $restaurant->id,
                'customer_name' => 'P'.$i,
                'customer_phone' => '0780000000',
                'delivery_type' => 'delivery',
                'table_number' => null,
                'total_price' => 5.00,
                'status' => 'pending',
            ]);
        }

        for ($i = 0; $i < 5; $i++) {
            Order::create([
                'restaurant_id' => $restaurant->id,
                'customer_name' => 'A'.$i,
                'customer_phone' => '0770000000',
                'delivery_type' => 'delivery',
                'table_number' => null,
                'total_price' => 8.00,
                'status' => 'accepted',
            ]);
        }

        $this->actingAs($user)
            ->getJson(route('orders.data', ['status' => 'pending', 'limit' => 10]))
            ->assertOk()
            ->assertJsonPath('meta.total', 12)
            ->assertJsonPath('meta.limit', 10)
            ->assertJsonCount(10, 'orders');
    }
}
