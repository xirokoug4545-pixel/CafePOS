<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'selected_options',
        'status', 'voided_by', 'void_reason',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'selected_options' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function voidedBy(): BelongsTo { return $this->belongsTo(User::class, 'voided_by'); }
}