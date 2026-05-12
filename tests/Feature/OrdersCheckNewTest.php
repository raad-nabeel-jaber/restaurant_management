<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersCheckNewTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_new_orders_returns_pending_count_for_authenticated_restaurant(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Test Restaurant',
            'slug' => 'test-rest-orders-check',
            'whatsapp_number' => '966501234567',
            'is_active' => true,
        ]);

        Order::create([
            'restaurant_id' => $restaurant->id,
            'customer_name' => 'عميل',
            'customer_phone' => '0799999999',
            'delivery_type' => 'delivery',
            'table_number' => null,
            'total_price' => 25.50,
            'status' => 'pending',
        ]);

        Order::create([
            'restaurant_id' => $restaurant->id,
            'customer_name' => 'عميل 2',
            'customer_phone' => '0788888888',
            'delivery_type' => 'delivery',
            'table_number' => null,
            'total_price' => 10.00,
            'status' => 'accepted',
        ]);

        $this->actingAs($user)
            ->getJson(route('orders.checkNew'))
            ->assertOk()
            ->assertJson(['new_orders_count' => 1]);
    }

    public function test_check_new_orders_requires_authentication(): void
    {
        $this->getJson(route('orders.checkNew'))
            ->assertUnauthorized();
    }
}
