<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DistributionAllocation extends Model
{
    protected $fillable = [
        'risk_prediction_id',
        'warehouse_id',
        'destination_region_id',
        'quantity_tons',
        'priority',
        'route_distance_km',
        'estimated_transit_days',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity_tons' => 'float',
            'route_distance_km' => 'float',
            'estimated_transit_days' => 'float',
        ];
    }

    public function riskPrediction(): BelongsTo
    {
        return $this->belongsTo(
            RiskPrediction::class
        );
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function destinationRegion(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'destination_region_id'
        );
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
