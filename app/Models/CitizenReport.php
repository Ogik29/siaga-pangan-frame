<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CitizenReport extends Model
{
    protected $fillable = [
        'user_id',
        'region_id',
        'commodity_id',
        'description',
        'photo_path',
        'issue_type',
        'urgency',
        'sentiment_score',
        'is_verified',
        'eligible_for_risk',
        'reported_at',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'sentiment_score' => 'float',
            'is_verified' => 'boolean',
            'eligible_for_risk' => 'boolean',
            'reported_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
