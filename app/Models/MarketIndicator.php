<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketIndicator extends Model
{
    protected $fillable = [
        'region_id',
        'commodity_id',
        'indicator_date',
        'yoy_pct',
        'mom_pct',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'indicator_date' => 'date',
            'yoy_pct' => 'float',
            'mom_pct' => 'float',
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
