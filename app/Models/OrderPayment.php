<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    protected $fillable = ['order_id', 'user_id', 'payment_type', 'amount', 'tip_amount', 'reference'];

    protected $casts = ['amount' => 'decimal:2', 'tip_amount' => 'decimal:2'];

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}