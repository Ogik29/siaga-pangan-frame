<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherRecord extends Model
{
    protected $fillable = [
        'region_id',
        'observed_date',
        'rainfall_mm',
        'rainfall_anomaly_mm',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'observed_date' => 'date',
            'rainfall_mm' => 'float',
            'rainfall_anomaly_mm' => 'float',
        ];
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
