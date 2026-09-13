<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TillSession extends Model
{
    protected $fillable = ['opened_by', 'closed_by', 'opening_float', 'expected_cash', 'counted_cash', 'discrepancy', 'discrepancy_notes', 'opened_at', 'closed_at'];

    protected $casts = ['opening_float' => 'decimal:2', 'expected_cash' => 'decimal:2', 'counted_cash' => 'decimal:2', 'discrepancy' => 'decimal:2', 'opened_at' => 'datetime', 'closed_at' => 'datetime'];

    public function openedBy(): BelongsTo { return $this->belongsTo(User::class, 'opened_by'); }

    public function closedBy(): BelongsTo { return $this->belongsTo(User::class, 'closed_by'); }
}