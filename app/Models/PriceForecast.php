<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceForecast extends Model
{
    protected $fillable = [
        'region_id',
        'commodity_id',
        'forecast_date',
        'predicted_price',
        'lower_bound',
        'upper_bound',
        'horizon_days',
        'model_name',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'forecast_date' => 'date',
            'predicted_price' => 'float',
            'lower_bound' => 'float',
            'upper_bound' => 'float',
            'generated_at' => 'datetime',
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
}
