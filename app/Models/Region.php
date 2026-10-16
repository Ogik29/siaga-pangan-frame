<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'code',
        'name',
        'province',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'is_active' => 'boolean',
        ];
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }

    public function priceRecords(): HasMany
    {
        return $this->hasMany(PriceRecord::class);
    }

    public function priceForecasts(): HasMany
    {
        return $this->hasMany(PriceForecast::class);
    }

    public function demandRecords(): HasMany
    {
        return $this->hasMany(DemandRecord::class);
    }

    public function weatherRecords(): HasMany
    {
        return $this->hasMany(WeatherRecord::class);
    }

    public function citizenReports(): HasMany
    {
        return $this->hasMany(CitizenReport::class);
    }

    public function riskPredictions(): HasMany
    {
        return $this->hasMany(RiskPrediction::class);
    }

    public function destinationShipments(): HasMany
    {
        return $this->hasMany(
            Shipment::class,
            'destination_region_id'
        );
    }
}
