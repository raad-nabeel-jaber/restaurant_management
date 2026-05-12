<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'restaurant_name' => 'Test Restaurant',
                'slug' => 'test-rest-'.$user->id,
                'whatsapp_number' => '0500000000',
                'whatsapp_orders_enabled' => '1',
                'is_active' => '1',
                'order_method' => 'whatsapp',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);

        $restaurant = $user->fresh()->restaurant;
        $this->assertNotNull($restaurant);
        $this->assertSame('Test Restaurant', $restaurant->name);
        $this->assertSame('test-rest-'.$user->id, $restaurant->slug);
        $this->assertSame('0500000000', $restaurant->whatsapp_number);
        $this->assertTrue($restaurant->is_active);
        $this->assertSame('whatsapp', $restaurant->order_method);
        $this->assertTrue($restaurant->whatsapp_orders_enabled);
    }

    public function test_whatsapp_orders_can_be_disabled_from_profile(): void
    {
        $user = User::factory()->create();
        $restaurant = $user->getOrCreateRestaurant();
        $restaurant->update(['whatsapp_orders_enabled' => true]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'restaurant_name' => $restaurant->name,
                'slug' => $restaurant->slug,
                'whatsapp_number' => '0501111111',
                'whatsapp_orders_enabled' => '0',
                'is_active' => '1',
                'order_method' => 'whatsapp',
            ]);

        $response->assertSessionHasNoErrors()->assertRedirect('/profile');
        $this->assertFalse($restaurant->fresh()->whatsapp_orders_enabled);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $restaurant = $user->getOrCreateRestaurant();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
                'restaurant_name' => $restaurant->name,
                'slug' => $restaurant->slug,
                'whatsapp_number' => $restaurant->whatsapp_number,
                'is_active' => $restaurant->is_active ? '1' : '0',
                'order_method' => $restaurant->order_method ?? 'whatsapp',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
