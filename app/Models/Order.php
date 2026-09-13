<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'status',
        'total_amount',
        'user_id', 'order_mode', 'table_name', 'subtotal', 'discount_amount', 'tip_amount',
        'payment_status', 'held_at', 'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'subtotal' => 'decimal:2', 'discount_amount' => 'decimal:2', 'tip_amount' => 'decimal:2',
        'held_at' => 'datetime', 'completed_at' => 'datetime',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany { return $this->hasMany(OrderPayment::class); }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}