<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Commodity;
use App\Models\PriceRecord;
use App\Models\DemandRecord;
use App\Models\StockRecord;
use App\Models\RiskPrediction;
use App\Models\CitizenReport;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $latestRiskDate =
            RiskPrediction::max(
                'prediction_date'
            );

        $highRiskRegions =
            $latestRiskDate
            ? RiskPrediction::query()
            ->whereDate(
                'prediction_date',
                $latestRiskDate
            )
            ->where(
                'risk_level',
                'High'
            )
            ->distinct()
            ->count('region_id')
            : 0;


        $criticalCommodity = null;

        if ($latestRiskDate) {
            $criticalCommodity =
                RiskPrediction::query()
                ->with('commodity')
                ->select(
                    'commodity_id',
                    DB::raw(
                        'AVG(risk_score) as average_score'
                    )
                )
                ->whereDate(
                    'prediction_date',
                    $latestRiskDate
                )
                ->groupBy(
                    'commodity_id'
                )
                ->orderByDesc(
                    'average_score'
                )
                ->first();
        }


        $communityReports =
            CitizenReport::count();


        /*
        |--------------------------------------------------------------------------
        | Stock Safety Indicator
        |--------------------------------------------------------------------------
        */

        $latestStockDate =
            StockRecord::max(
                'record_date'
            );

        $stockSafePercentage = 0;

        if ($latestStockDate) {
            $stocks =
                StockRecord::query()
                ->with('warehouse')
                ->whereDate(
                    'record_date',
                    $latestStockDate
                )
                ->get();

            $demands =
                DemandRecord::query()
                ->whereDate(
                    'record_date',
                    $latestStockDate
                )
                ->get()
                ->keyBy(
                    fn($demand) =>
                    $demand->region_id
                        . '-'
                        . $demand->commodity_id
                );

            $safe = 0;
            $total = 0;

            foreach ($stocks as $stock) {
                $key =
                    $stock->warehouse->region_id
                    . '-'
                    . $stock->commodity_id;

                $demand =
                    $demands->get($key);

                if (!$demand) {
                    continue;
                }

                $available =
                    max(
                        0,
                        $stock->quantity_tons
                            - $stock->reserved_tons
                    );

                $coverage =
                    $demand->average_daily_demand_tons > 0
                    ? $available
                    / $demand
                    ->average_daily_demand_tons
                    : 0;

                $total++;

                /*
                 * Prototype UI rule:
                 * >= 7 days is considered safe.
                 */
                if ($coverage >= 7) {
                    $safe++;
                }
            }

            $stockSafePercentage =
                $total > 0
                ? round(
                    ($safe / $total) * 100
                )
                : 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Default Chart
        |--------------------------------------------------------------------------
        */

        $defaultRegion =
            Region::where(
                'code',
                'NTT'
            )->first();

        $defaultCommodity =
            Commodity::where(
                'code',
                'RICE'
            )->first();


        $priceTrend = collect();

        $demandTrend = collect();

        if (
            $defaultRegion &&
            $defaultCommodity
        ) {
            $priceTrend =
                PriceRecord::query()
                ->where(
                    'region_id',
                    $defaultRegion->id
                )
                ->where(
                    'commodity_id',
                    $defaultCommodity->id
                )
                ->orderBy('price_date')
                ->limit(7)
                ->get();

            $demandTrend =
                DemandRecord::query()
                ->where(
                    'region_id',
                    $defaultRegion->id
                )
                ->where(
                    'commodity_id',
                    $defaultCommodity->id
                )
                ->orderBy('record_date')
                ->limit(7)
                ->get();
        }


        $riskList =
            RiskPrediction::query()
            ->with([
                'region',
                'commodity',
            ])
            ->orderByDesc('risk_score')
            ->limit(10)
            ->get();


        return view(
            'dashboard.index',
            compact(
                'highRiskRegions',
                'criticalCommodity',
                'communityReports',
                'stockSafePercentage',
                'priceTrend',
                'demandTrend',
                'riskList',
                'defaultRegion',
                'defaultCommodity'
            )
        );
    }
}
