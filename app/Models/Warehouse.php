<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'region_id',
        'code',
        'name',
        'latitude',
        'longitude',
        'capacity_tons',
        'is_reserve',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'capacity_tons' => 'float',
            'is_reserve' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function stockRecords(): HasMany
    {
        return $this->hasMany(StockRecord::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(DistributionAllocation::class);
    }

    public function outgoingShipments(): HasMany
    {
        return $this->hasMany(
            Shipment::class,
            'origin_warehouse_id'
        );
    }
}
