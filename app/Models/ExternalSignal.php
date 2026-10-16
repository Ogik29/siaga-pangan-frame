<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExternalSignal extends Model
{
    protected $fillable = [
        'region_id',
        'commodity_id',
        'source_type',
        'keyword',
        'volume',
        'triggers_alert',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'volume' => 'integer',
            'triggers_alert' => 'boolean',
            'recorded_at' => 'datetime',
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
