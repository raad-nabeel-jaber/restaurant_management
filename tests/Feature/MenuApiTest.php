<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_endpoint_returns_restaurant_categories_and_available_products(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Demo Restaurant',
            'slug' => 'demo-menu-json-endpoint',
            'whatsapp_number' => '+962799999999',
            'is_active' => true,
        ]);

        $category = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main Dishes',
            'sort_order' => 1,
        ]);

        $availableProduct = Product::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'name' => 'Chicken Burger',
            'description' => 'Grilled chicken burger',
            'price' => 4.50,
            'is_available' => true,
        ]);

        Product::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'name' => 'Hidden Product',
            'price' => 9.99,
            'is_available' => false,
        ]);

        $response = $this->getJson("/menu/{$restaurant->slug}");

        $response
            ->assertOk()
            ->assertJsonPath('restaurant.slug', $restaurant->slug)
            ->assertJsonPath('restaurant.order_method', 'whatsapp')
            ->assertJsonPath('restaurant.checkout_method', 'whatsapp')
            ->assertJsonPath('restaurant.whatsapp_orders_enabled', true)
            ->assertJsonPath('categories.0.name', $category->name)
            ->assertJsonPath('categories.0.products.0.id', $availableProduct->id)
            ->assertJsonMissing([
                'name' => 'Hidden Product',
            ]);
    }

    public function test_store_order_whatsapp_returns_redirect_url(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Demo Restaurant',
            'slug' => 'demo-whatsapp-order',
            'whatsapp_number' => '+962799999999',
            'is_active' => true,
            'order_method' => 'whatsapp',
        ]);

        $category = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main Dishes',
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'name' => 'Chicken Burger',
            'price' => 4.50,
            'is_available' => true,
        ]);

        $payload = [
            'customer_name' => 'Ahmad',
            'customer_phone' => '0799999999',
            'delivery_type' => 'delivery',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];

        $response = $this->postJson("/menu/{$restaurant->slug}/order", $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('method', 'whatsapp')
            ->assertJsonPath('message', 'تم استلام الطلب بنجاح.')
            ->assertJsonPath('total_price', 9);

        $this->assertNotEmpty($response->json('redirect_url'));
        $this->assertSame($response->json('redirect_url'), $response->json('whatsapp_url'));

        $this->assertDatabaseHas('orders', [
            'restaurant_id' => $restaurant->id,
            'customer_name' => 'Ahmad',
            'delivery_type' => 'delivery',
            'status' => 'pending',
        ]);

        $order = Order::query()->firstOrFail();

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 4.5,
        ]);
    }

    public function test_store_order_dashboard_returns_success_without_redirect_url(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'Dashboard Order Restaurant',
            'slug' => 'dashboard-order-rest',
            'whatsapp_number' => '+962799999999',
            'is_active' => true,
            'order_method' => 'dashboard',
        ]);

        $category = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main Dishes',
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'name' => 'Falafel',
            'price' => 3.00,
            'is_available' => true,
        ]);

        $payload = [
            'customer_name' => 'Sara',
            'customer_phone' => '0788888888',
            'delivery_type' => 'delivery',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $response = $this->postJson("/menu/{$restaurant->slug}/order", $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('method', 'dashboard')
            ->assertJsonPath('message', 'تم استلام طلبك بنجاح وجاري تجهيزه!');

        $this->assertArrayNotHasKey('redirect_url', $response->json());
        $this->assertDatabaseHas('orders', [
            'restaurant_id' => $restaurant->id,
            'customer_name' => 'Sara',
            'status' => 'pending',
        ]);
    }

    public function test_menu_checkout_method_is_dashboard_when_whatsapp_orders_disabled(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'WA Off Restaurant',
            'slug' => 'wa-off-menu',
            'whatsapp_number' => '+962799999999',
            'is_active' => true,
            'order_method' => 'whatsapp',
            'whatsapp_orders_enabled' => false,
        ]);

        $response = $this->getJson("/menu/{$restaurant->slug}");

        $response
            ->assertOk()
            ->assertJsonPath('restaurant.order_method', 'whatsapp')
            ->assertJsonPath('restaurant.checkout_method', 'dashboard')
            ->assertJsonPath('restaurant.whatsapp_orders_enabled', false);
    }

    public function test_store_order_uses_dashboard_when_whatsapp_orders_disabled(): void
    {
        $user = User::factory()->create();

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name' => 'WA Off Order',
            'slug' => 'wa-off-order',
            'whatsapp_number' => '+962799999999',
            'is_active' => true,
            'order_method' => 'whatsapp',
            'whatsapp_orders_enabled' => false,
        ]);

        $category = Category::create([
            'restaurant_id' => $restaurant->id,
            'name' => 'Main',
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $category->id,
            'name' => 'Item',
            'price' => 2.00,
            'is_available' => true,
        ]);

        $response = $this->postJson("/menu/{$restaurant->slug}/order", [
            'customer_name' => 'Ali',
            'customer_phone' => '0777777777',
            'delivery_type' => 'delivery',
            'items' => [['product_id' => $product->id, 'quantity' => 1]],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('method', 'dashboard');

        $this->assertArrayNotHasKey('redirect_url', $response->json());
    }
}
