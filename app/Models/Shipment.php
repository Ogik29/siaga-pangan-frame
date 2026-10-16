<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shipment extends Model
{
    protected $fillable = [
        'distribution_allocation_id',
        'commodity_id',
        'origin_warehouse_id',
        'destination_region_id',
        'sender_user_id',
        'receiver_user_id',
        'batch_id',
        'destination_name',
        'quantity_tons',
        'status',
        'prepared_at',
        'dispatched_at',
        'arrived_at',
        'distributed_at',
        'sender_confirmed_at',
        'receiver_confirmed_at',
        'qr_token',
    ];

    protected function casts(): array
    {
        return [
            'quantity_tons' => 'float',
            'prepared_at' => 'datetime',
            'dispatched_at' => 'datetime',
            'arrived_at' => 'datetime',
            'distributed_at' => 'datetime',
            'sender_confirmed_at' => 'datetime',
            'receiver_confirmed_at' => 'datetime',
        ];
    }

    public function allocation(): BelongsTo
    {
        return $this->belongsTo(
            DistributionAllocation::class,
            'distribution_allocation_id'
        );
    }

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    public function originWarehouse(): BelongsTo
    {
        return $this->belongsTo(
            Warehouse::class,
            'origin_warehouse_id'
        );
    }

    public function destinationRegion(): BelongsTo
    {
        return $this->belongsTo(
            Region::class,
            'destination_region_id'
        );
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'sender_user_id'
        );
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'receiver_user_id'
        );
    }

    public function ledgerTransaction(): HasOne
    {
        return $this->hasOne(
            LedgerTransaction::class
        );
    }
}
