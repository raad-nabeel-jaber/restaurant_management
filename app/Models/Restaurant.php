<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    use HasFactory;

    public const ORDER_METHOD_WHATSAPP = 'whatsapp';

    public const ORDER_METHOD_DASHBOARD = 'dashboard';

    /** @var list<string> */
    public const ORDER_METHODS = [
        self::ORDER_METHOD_WHATSAPP,
        self::ORDER_METHOD_DASHBOARD,
    ];

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'whatsapp_number',
        'logo',
        'is_active',
        'order_method',
        'whatsapp_orders_enabled',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'whatsapp_orders_enabled' => 'boolean',
        ];
    }

    /**
     * How the public menu checkout should behave (WhatsApp redirect vs silent dashboard).
     */
    public function customerCheckoutMethod(): string
    {
        if (! ($this->whatsapp_orders_enabled ?? true)) {
            return self::ORDER_METHOD_DASHBOARD;
        }

        $method = in_array($this->order_method, self::ORDER_METHODS, true)
            ? $this->order_method
            : self::ORDER_METHOD_WHATSAPP;

        if ($method !== self::ORDER_METHOD_WHATSAPP) {
            return self::ORDER_METHOD_DASHBOARD;
        }

        $phone = preg_replace('/\D+/', '', (string) ($this->whatsapp_number ?? ''));

        return $phone !== '' ? self::ORDER_METHOD_WHATSAPP : self::ORDER_METHOD_DASHBOARD;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
