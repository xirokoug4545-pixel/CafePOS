<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'is_seasonal',
        'tracks_inventory',
        'stock_quantity',
        'low_stock_threshold',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_seasonal' => 'boolean',
        'tracks_inventory' => 'boolean',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productOptions(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }
}