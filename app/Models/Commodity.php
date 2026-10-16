<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commodity extends Model
{
    protected $fillable = [
        'code',
        'name',
        'unit',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
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

    public function citizenReports(): HasMany
    {
        return $this->hasMany(CitizenReport::class);
    }

    public function riskPredictions(): HasMany
    {
        return $this->hasMany(RiskPrediction::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
