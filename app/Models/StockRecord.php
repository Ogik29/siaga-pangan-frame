<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockRecord extends Model
{
    protected $fillable = [
        'warehouse_id',
        'commodity_id',
        'record_date',
        'quantity_tons',
        'reserved_tons',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'quantity_tons' => 'float',
            'reserved_tons' => 'float',
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    public function getAvailableTonsAttribute(): float
    {
        return max(
            0,
            $this->quantity_tons - $this->reserved_tons
        );
    }
}
