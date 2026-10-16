<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandRecord extends Model
{
    protected $fillable = [
        'region_id',
        'commodity_id',
        'record_date',
        'average_daily_demand_tons',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'record_date' => 'date',
            'average_daily_demand_tons' => 'float',
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
