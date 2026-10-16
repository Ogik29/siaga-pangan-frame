<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskFactor extends Model
{
    protected $fillable = [
        'risk_prediction_id',
        'factor_code',
        'factor_name',
        'contribution_percentage',
        'raw_value',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'contribution_percentage' => 'float',
            'raw_value' => 'float',
        ];
    }

    public function riskPrediction(): BelongsTo
    {
        return $this->belongsTo(
            RiskPrediction::class
        );
    }
}
