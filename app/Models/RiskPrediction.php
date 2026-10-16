<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskPrediction extends Model
{
    protected $fillable = [
        'region_id',
        'commodity_id',
        'prediction_date',
        'horizon_days',
        'forecast_price_change_pct',
        'price_deviation_sd',
        'stock_coverage_days',
        'shipment_delay_days',
        'rainfall_anomaly_mm',
        'verified_report_volume',
        'risk_score',
        'risk_level',
        'model_name',
        'model_source',
        'input_snapshot',
        'alerted_at',
    ];

    protected function casts(): array
    {
        return [
            'prediction_date' => 'date',
            'forecast_price_change_pct' => 'float',
            'price_deviation_sd' => 'float',
            'stock_coverage_days' => 'float',
            'shipment_delay_days' => 'float',
            'rainfall_anomaly_mm' => 'float',
            'risk_score' => 'float',
            'input_snapshot' => 'array',
            'alerted_at' => 'datetime',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    public function factors(): HasMany
    {
        return $this->hasMany(RiskFactor::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(
            DistributionAllocation::class
        );
    }
}
