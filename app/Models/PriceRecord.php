<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceRecord extends Model
{
    protected $fillable = [
        'region_id',
        'commodity_id',
        'price_date',
        'market_type',
        'price',
        'previous_price',
        'change_pct',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'price_date' => 'date',
            'price' => 'float',
            'previous_price' => 'float',
            'change_pct' => 'float',
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
