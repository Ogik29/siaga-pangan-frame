<?php
// Enam feature tersebut persis mengikuti paper: forecast price change, price deviation, stock coverage, shipment delay, 
// rainfall anomaly, dan verified report volume. Threshold awalnya juga 70 = High, 40 = Medium, <40 = Low.

namespace App\Services;

use App\Models\Region;
use App\Models\Commodity;
use App\Models\PriceRecord;
use App\Models\PriceForecast;
use App\Models\DemandRecord;
use App\Models\StockRecord;
use App\Models\WeatherRecord;
use App\Models\CitizenReport;
use App\Models\Shipment;
use App\Models\RiskPrediction;

use Illuminate\Support\Carbon;

class RiskAnalysisService
{
    public function __construct(
        private AiPredictionService $aiPredictionService
    ) {}

    public function analyze(
        int $regionId,
        int $commodityId,
        int $horizonDays = 7
    ): RiskPrediction {
        $region = Region::findOrFail($regionId);

        $commodity = Commodity::findOrFail(
            $commodityId
        );

        /*
        |--------------------------------------------------------------------------
        | Latest Price
        |--------------------------------------------------------------------------
        */

        $currentPriceRecord = PriceRecord::query()
            ->where('region_id', $regionId)
            ->where('commodity_id', $commodityId)
            ->orderByDesc('price_date')
            ->first();

        $analysisDate = $currentPriceRecord
            ? Carbon::parse(
                $currentPriceRecord->price_date
            )->endOfDay()
            : now();

        $currentPrice = $currentPriceRecord
            ? (float) $currentPriceRecord->price
            : 0;


        /*
        |--------------------------------------------------------------------------
        | National Mean & Standard Deviation
        |--------------------------------------------------------------------------
        */

        $latestRegionalPrices = PriceRecord::query()
            ->where('commodity_id', $commodityId)
            ->whereDate(
                'price_date',
                $currentPriceRecord?->price_date
            )
            ->pluck('price')
            ->map(fn($price) => (float) $price)
            ->values();

        $nationalMean = $latestRegionalPrices->avg();

        $variance = 0;

        if ($latestRegionalPrices->count() > 1) {
            $variance = $latestRegionalPrices
                ->map(
                    fn($price) =>
                    pow($price - $nationalMean, 2)
                )
                ->avg();
        }

        $standardDeviation = sqrt($variance);

        $priceDeviationSd = $standardDeviation > 0
            ? (
                ($currentPrice - $nationalMean)
                / $standardDeviation
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Forecast
        |--------------------------------------------------------------------------
        */

        $forecast = PriceForecast::query()
            ->where('region_id', $regionId)
            ->where('commodity_id', $commodityId)
            ->where(
                'forecast_date',
                '>=',
                $currentPriceRecord?->price_date
            )
            ->orderBy('forecast_date')
            ->first();

        $forecastPriceChangePct = 0;

        if (
            $forecast &&
            $currentPrice > 0
        ) {
            $forecastPriceChangePct =
                (
                    (
                        $forecast->predicted_price
                        - $currentPrice
                    )
                    / $currentPrice
                ) * 100;
        }


        /*
        |--------------------------------------------------------------------------
        | Stock Coverage
        |--------------------------------------------------------------------------
        */

        $warehouseIds = Region::query()
            ->find($regionId)
            ->warehouses()
            ->pluck('id');

        $stockRecords = StockRecord::query()
            ->whereIn(
                'warehouse_id',
                $warehouseIds
            )
            ->where(
                'commodity_id',
                $commodityId
            )
            ->where(
                'record_date',
                '<=',
                $analysisDate->toDateString()
            )
            ->orderByDesc('record_date')
            ->get()
            ->unique('warehouse_id');

        $availableStock = $stockRecords->sum(
            fn($stock) =>
            max(
                0,
                $stock->quantity_tons
                    - $stock->reserved_tons
            )
        );

        $demand = DemandRecord::query()
            ->where('region_id', $regionId)
            ->where('commodity_id', $commodityId)
            ->where(
                'record_date',
                '<=',
                $analysisDate->toDateString()
            )
            ->orderByDesc('record_date')
            ->first();

        $dailyDemand = $demand
            ? (float) $demand->average_daily_demand_tons
            : 0;

        $stockCoverageDays = $dailyDemand > 0
            ? $availableStock / $dailyDemand
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Shipment Delay
        |--------------------------------------------------------------------------
        */

        $historicalShipments = Shipment::query()
            ->where(
                'destination_region_id',
                $regionId
            )
            ->where(
                'commodity_id',
                $commodityId
            )
            ->whereNotNull('dispatched_at')
            ->whereNotNull('arrived_at')
            ->where(
                'arrived_at',
                '<=',
                $analysisDate
            )
            ->get();

        $shipmentDelayDays =
            $historicalShipments->count() > 0
            ? $historicalShipments
            ->map(
                fn($shipment) =>
                $shipment->dispatched_at
                    ->diffInHours(
                        $shipment->arrived_at
                    ) / 24
            )
            ->avg()
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Rainfall Anomaly
        |--------------------------------------------------------------------------
        */

        $weather = WeatherRecord::query()
            ->where('region_id', $regionId)
            ->where(
                'observed_date',
                '<=',
                $analysisDate->toDateString()
            )
            ->orderByDesc('observed_date')
            ->first();

        $rainfallAnomaly = $weather
            ? (float) $weather->rainfall_anomaly_mm
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Verified Community Reports
        |--------------------------------------------------------------------------
        */

        $reportStart = $analysisDate
            ->copy()
            ->subDay();

        $verifiedReportVolume =
            CitizenReport::query()
            ->where('region_id', $regionId)
            ->where(
                'commodity_id',
                $commodityId
            )
            ->where('is_verified', true)
            ->where('eligible_for_risk', true)
            ->whereBetween(
                'reported_at',
                [
                    $reportStart,
                    $analysisDate,
                ]
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Features
        |--------------------------------------------------------------------------
        */

        $features = [
            'forecast_price_change_pct' =>
            round(
                $forecastPriceChangePct,
                4
            ),

            'price_deviation_sd' =>
            round(
                $priceDeviationSd,
                4
            ),

            'stock_coverage_days' =>
            round(
                $stockCoverageDays,
                4
            ),

            'shipment_delay_days' =>
            round(
                $shipmentDelayDays,
                4
            ),

            'rainfall_anomaly_mm' =>
            round(
                $rainfallAnomaly,
                4
            ),

            'verified_report_volume' =>
            $verifiedReportVolume,
        ];


        /*
        |--------------------------------------------------------------------------
        | AI Prediction
        |--------------------------------------------------------------------------
        */

        $aiResult =
            $this->aiPredictionService
            ->predict($features);

        if ($aiResult) {
            $riskScore = (float) (
                $aiResult['score'] ?? 0
            );

            $riskLevel =
                $this->riskLevel($riskScore);

            $factors =
                $aiResult['factors'] ?? [];

            $modelSource = 'ai';
        } else {
            $fallback =
                $this->fallbackPrediction(
                    $features
                );

            $riskScore = $fallback['score'];
            $riskLevel = $fallback['level'];
            $factors = $fallback['factors'];

            $modelSource = 'fallback';
        }


        /*
        |--------------------------------------------------------------------------
        | Save Prediction
        |--------------------------------------------------------------------------
        */

        $prediction =
            RiskPrediction::updateOrCreate(
                [
                    'region_id' => $regionId,
                    'commodity_id' => $commodityId,
                    'prediction_date' =>
                    $analysisDate->toDateString(),
                    'horizon_days' => $horizonDays,
                ],
                [
                    'forecast_price_change_pct' =>
                    $features['forecast_price_change_pct'],

                    'price_deviation_sd' =>
                    $features['price_deviation_sd'],

                    'stock_coverage_days' =>
                    $features['stock_coverage_days'],

                    'shipment_delay_days' =>
                    $features['shipment_delay_days'],

                    'rainfall_anomaly_mm' =>
                    $features['rainfall_anomaly_mm'],

                    'verified_report_volume' =>
                    $features['verified_report_volume'],

                    'risk_score' => $riskScore,

                    'risk_level' => $riskLevel,

                    'model_name' =>
                    $aiResult
                        ? 'XGBoost'
                        : 'Prototype Risk Engine',

                    'model_source' => $modelSource,

                    'input_snapshot' => $features,
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | Risk Factors
        |--------------------------------------------------------------------------
        */

        $prediction->factors()->delete();

        foreach ($factors as $factor) {
            $prediction->factors()->create([
                'factor_code' =>
                $factor['code'] ?? 'UNKNOWN',

                'factor_name' =>
                $factor['name'] ?? 'Unknown Factor',

                'contribution_percentage' =>
                $factor['percentage'] ?? 0,

                'raw_value' =>
                $factor['value'] ?? null,

                'description' =>
                $factor['description'] ?? null,
            ]);
        }

        return $prediction->load([
            'region',
            'commodity',
            'factors',
        ]);
    }


    private function fallbackPrediction(
        array $features
    ): array {
        $points = [];

        $points['forecast_price_change'] =
            min(
                max(
                    $features['forecast_price_change_pct'] / 20,
                    0
                ),
                1
            ) * 25;

        $points['price_deviation'] =
            min(
                max(
                    $features['price_deviation_sd'] / 2,
                    0
                ),
                1
            ) * 20;

        $points['low_stock'] =
            max(
                0,
                1 - min(
                    $features['stock_coverage_days'] / 14,
                    1
                )
            ) * 20;

        $points['shipment_delay'] =
            min(
                max(
                    $features['shipment_delay_days'] / 5,
                    0
                ),
                1
            ) * 15;

        $points['rainfall_anomaly'] =
            min(
                abs(
                    $features['rainfall_anomaly_mm']
                ) / 100,
                1
            ) * 10;

        $points['verified_reports'] =
            min(
                $features['verified_report_volume'] / 3,
                1
            ) * 10;

        $score = round(
            array_sum($points),
            2
        );

        $level =
            $this->riskLevel($score);

        $factors = [];

        foreach ($points as $code => $point) {
            $percentage = $score > 0
                ? round(
                    ($point / $score) * 100,
                    2
                )
                : 0;

            $factors[] = [
                'code' => strtoupper($code),
                'name' =>
                ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $code
                    )
                ),
                'percentage' =>
                $percentage,
                'value' =>
                $features[match ($code) {
                    'forecast_price_change' =>
                    'forecast_price_change_pct',

                    'price_deviation' =>
                    'price_deviation_sd',

                    'low_stock' =>
                    'stock_coverage_days',

                    'shipment_delay' =>
                    'shipment_delay_days',

                    'rainfall_anomaly' =>
                    'rainfall_anomaly_mm',

                    'verified_reports' =>
                    'verified_report_volume',

                    default =>
                    'risk_score',
                }] ?? null,

                'description' =>
                'Prototype contribution based on the current risk feature.',
            ];
        }

        return [
            'score' => $score,
            'level' => $level,
            'factors' => $factors,
        ];
    }


    private function riskLevel(
        float $score
    ): string {
        return match (true) {
            $score >= 70 => 'High',
            $score >= 40 => 'Medium',
            default => 'Low',
        };
    }
}
