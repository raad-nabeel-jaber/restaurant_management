<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function restaurant(): HasOne
    {
        return $this->hasOne(Restaurant::class);
    }

    /**
     * Users created outside registration (seeders, legacy) may lack a restaurant row.
     */
    public function getOrCreateRestaurant(): Restaurant
    {
        if ($this->restaurant) {
            return $this->restaurant;
        }

        return $this->restaurant()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name).'-'.$this->id.'-'.uniqid(),
            'whatsapp_number' => '',
            'is_active' => true,
            'order_method' => Restaurant::ORDER_METHOD_WHATSAPP,
            'whatsapp_orders_enabled' => true,
        ]);
    }
}
