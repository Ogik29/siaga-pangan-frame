<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerTransaction extends Model
{
    protected $fillable = [
        'shipment_id',
        'record_hash',
        'blockchain_tx_hash',
        'network',
        'status',
        'recorded_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }
}
