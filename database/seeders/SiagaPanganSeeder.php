<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Region;
use App\Models\Commodity;
use App\Models\Warehouse;
use App\Models\PriceRecord;
use App\Models\PriceForecast;
use App\Models\DemandRecord;
use App\Models\StockRecord;
use App\Models\WeatherRecord;
use App\Models\MarketIndicator;
use App\Models\ExternalSignal;
use App\Models\CitizenReport;
use App\Models\RiskPrediction;
use App\Models\RiskFactor;
use App\Models\DistributionAllocation;
use App\Models\Shipment;
use App\Models\LedgerTransaction;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class SiagaPanganSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $government = User::updateOrCreate(
            ['email' => 'government@siagapangan.test'],
            [
                'name' => 'Food Security Officer',
                'password' => Hash::make('password'),
                'role' => 'government',
                'organization' => 'National Food Agency',
            ]
        );

        $distributor = User::updateOrCreate(
            ['email' => 'distributor@siagapangan.test'],
            [
                'name' => 'Distribution Officer',
                'password' => Hash::make('password'),
                'role' => 'distributor',
                'organization' => 'Food Distribution Partner',
            ]
        );

        $citizen1 = User::updateOrCreate(
            ['email' => 'citizen1@siagapangan.test'],
            [
                'name' => 'Citizen One',
                'password' => Hash::make('password'),
                'role' => 'citizen',
            ]
        );

        $citizen2 = User::updateOrCreate(
            ['email' => 'citizen2@siagapangan.test'],
            [
                'name' => 'Citizen Two',
                'password' => Hash::make('password'),
                'role' => 'citizen',
            ]
        );

        $citizen3 = User::updateOrCreate(
            ['email' => 'citizen3@siagapangan.test'],
            [
                'name' => 'Citizen Three',
                'password' => Hash::make('password'),
                'role' => 'citizen',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Regions
        |--------------------------------------------------------------------------
        */

        $ntt = Region::updateOrCreate(
            ['code' => 'NTT'],
            [
                'name' => 'East Nusa Tenggara',
                'province' => 'East Nusa Tenggara',
                'latitude' => -10.1772,
                'longitude' => 123.6070,
                'is_active' => true,
            ]
        );

        $makassar = Region::updateOrCreate(
            ['code' => 'MAKASSAR'],
            [
                'name' => 'Makassar',
                'province' => 'South Sulawesi',
                'latitude' => -5.1477,
                'longitude' => 119.4327,
                'is_active' => true,
            ]
        );

        $surabaya = Region::updateOrCreate(
            ['code' => 'SURABAYA'],
            [
                'name' => 'Surabaya',
                'province' => 'East Java',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
                'is_active' => true,
            ]
        );

        $jakarta = Region::updateOrCreate(
            ['code' => 'JAKARTA'],
            [
                'name' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'is_active' => true,
            ]
        );

        $madura = Region::updateOrCreate(
            ['code' => 'MADURA'],
            [
                'name' => 'Madura',
                'province' => 'East Java',
                'latitude' => -7.0253,
                'longitude' => 113.3400,
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Commodities
        |--------------------------------------------------------------------------
        */

        $rice = Commodity::updateOrCreate(
            ['code' => 'RICE'],
            [
                'name' => 'Rice',
                'unit' => 'ton',
                'category' => 'Staple Food',
                'is_active' => true,
            ]
        );

        $cookingOil = Commodity::updateOrCreate(
            ['code' => 'COOKING_OIL'],
            [
                'name' => 'Cooking Oil',
                'unit' => 'ton',
                'category' => 'Staple Food',
                'is_active' => true,
            ]
        );

        $chili = Commodity::updateOrCreate(
            ['code' => 'CHILI'],
            [
                'name' => 'Chili',
                'unit' => 'ton',
                'category' => 'Staple Food',
                'is_active' => true,
            ]
        );

        $sugar = Commodity::updateOrCreate(
            ['code' => 'SUGAR'],
            [
                'name' => 'Sugar',
                'unit' => 'ton',
                'category' => 'Staple Food',
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Warehouses
        |--------------------------------------------------------------------------
        */

        $makassarWarehouse = Warehouse::updateOrCreate(
            ['code' => 'WH-MKS'],
            [
                'region_id' => $makassar->id,
                'name' => 'Makassar Warehouse',
                'latitude' => -5.1477,
                'longitude' => 119.4327,
                'capacity_tons' => 100,
                'is_reserve' => false,
                'is_active' => true,
            ]
        );

        $surabayaWarehouse = Warehouse::updateOrCreate(
            ['code' => 'WH-SBY'],
            [
                'region_id' => $surabaya->id,
                'name' => 'Surabaya Warehouse',
                'latitude' => -7.2575,
                'longitude' => 112.7521,
                'capacity_tons' => 100,
                'is_reserve' => false,
                'is_active' => true,
            ]
        );

        $localReserve = Warehouse::updateOrCreate(
            ['code' => 'WH-NTT-RESERVE'],
            [
                'region_id' => $ntt->id,
                'name' => 'Local Reserve',
                'latitude' => -10.1772,
                'longitude' => 123.6070,
                'capacity_tons' => 50,
                'is_reserve' => true,
                'is_active' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Base Date
        |--------------------------------------------------------------------------
        */

        $baseDate = Carbon::create(
            2026,
            6,
            15,
            12,
            0,
            0
        );


        /*
        |--------------------------------------------------------------------------
        | Price History - NTT Rice
        |--------------------------------------------------------------------------
        */

        $prices = [
            15000,
            15200,
            15100,
            15600,
            15900,
            16200,
            16000,
        ];

        foreach ($prices as $index => $price) {
            $date = $baseDate
                ->copy()
                ->subDays(6 - $index);

            PriceRecord::updateOrCreate(
                [
                    'region_id' => $ntt->id,
                    'commodity_id' => $rice->id,
                    'price_date' => $date->toDateString(),
                    'market_type' => 'traditional',
                ],
                [
                    'price' => $price,
                    'previous_price' => $index > 0
                        ? $prices[$index - 1]
                        : null,
                    'change_pct' => $index > 0
                        ? (($price - $prices[$index - 1])
                            / $prices[$index - 1]) * 100
                        : null,
                    'source' => 'PIHPS',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Current Regional Prices
        |--------------------------------------------------------------------------
        */

        $regionalPrices = [
            [$ntt, 16000],
            [$makassar, 12000],
            [$surabaya, 12500],
            [$jakarta, 13000],
            [$madura, 13500],
        ];

        foreach ($regionalPrices as [$region, $price]) {
            PriceRecord::updateOrCreate(
                [
                    'region_id' => $region->id,
                    'commodity_id' => $rice->id,
                    'price_date' => $baseDate->toDateString(),
                    'market_type' => 'traditional',
                ],
                [
                    'price' => $price,
                    'previous_price' => null,
                    'change_pct' => null,
                    'source' => 'PIHPS',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Price Forecast - 7 Days
        |--------------------------------------------------------------------------
        */

        $forecastPrices = [
            19000,
            19200,
            19500,
            19700,
            19800,
            20000,
            20200,
        ];

        foreach ($forecastPrices as $index => $price) {
            $forecastDate = $baseDate
                ->copy()
                ->addDays($index + 1);

            PriceForecast::updateOrCreate(
                [
                    'region_id' => $ntt->id,
                    'commodity_id' => $rice->id,
                    'forecast_date' => $forecastDate->toDateString(),
                    'model_name' => 'Prophet',
                ],
                [
                    'predicted_price' => $price,
                    'lower_bound' => $price - 500,
                    'upper_bound' => $price + 500,
                    'horizon_days' => 7,
                    'generated_at' => $baseDate,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Demand
        |--------------------------------------------------------------------------
        */

        $nttDemand = [
            0.30,
            0.32,
            0.34,
            0.35,
            0.37,
            0.39,
            0.40,
        ];

        foreach ($nttDemand as $index => $demand) {
            $date = $baseDate
                ->copy()
                ->subDays(6 - $index);

            DemandRecord::updateOrCreate(
                [
                    'region_id' => $ntt->id,
                    'commodity_id' => $rice->id,
                    'record_date' => $date->toDateString(),
                ],
                [
                    'average_daily_demand_tons' => $demand,
                    'source' => 'Regional Demand Estimate',
                ]
            );
        }

        DemandRecord::updateOrCreate(
            [
                'region_id' => $makassar->id,
                'commodity_id' => $rice->id,
                'record_date' => $baseDate->toDateString(),
            ],
            [
                'average_daily_demand_tons' => 0.60,
                'source' => 'Regional Demand Estimate',
            ]
        );

        DemandRecord::updateOrCreate(
            [
                'region_id' => $surabaya->id,
                'commodity_id' => $rice->id,
                'record_date' => $baseDate->toDateString(),
            ],
            [
                'average_daily_demand_tons' => 0.60,
                'source' => 'Regional Demand Estimate',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Stock
        |--------------------------------------------------------------------------
        */

        StockRecord::updateOrCreate(
            [
                'warehouse_id' => $makassarWarehouse->id,
                'commodity_id' => $rice->id,
                'record_date' => $baseDate->toDateString(),
            ],
            [
                'quantity_tons' => 5,
                'reserved_tons' => 0,
                'source' => 'Warehouse Input',
            ]
        );

        StockRecord::updateOrCreate(
            [
                'warehouse_id' => $surabayaWarehouse->id,
                'commodity_id' => $rice->id,
                'record_date' => $baseDate->toDateString(),
            ],
            [
                'quantity_tons' => 3,
                'reserved_tons' => 0,
                'source' => 'Warehouse Input',
            ]
        );

        StockRecord::updateOrCreate(
            [
                'warehouse_id' => $localReserve->id,
                'commodity_id' => $rice->id,
                'record_date' => $baseDate->toDateString(),
            ],
            [
                'quantity_tons' => 2,
                'reserved_tons' => 0,
                'source' => 'Warehouse Input',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Weather
        |--------------------------------------------------------------------------
        */

        WeatherRecord::updateOrCreate(
            [
                'region_id' => $ntt->id,
                'observed_date' => $baseDate->toDateString(),
            ],
            [
                'rainfall_mm' => 30,
                'rainfall_anomaly_mm' => -100,
                'source' => 'BMKG',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Market Indicator
        |--------------------------------------------------------------------------
        */

        MarketIndicator::updateOrCreate(
            [
                'region_id' => $ntt->id,
                'commodity_id' => $rice->id,
                'indicator_date' => $baseDate->toDateString(),
            ],
            [
                'yoy_pct' => 8.5,
                'mom_pct' => 3.2,
                'source' => 'SP2KP',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | External Signals
        |--------------------------------------------------------------------------
        */

        ExternalSignal::create([
            'region_id' => $ntt->id,
            'commodity_id' => $rice->id,
            'source_type' => 'search',
            'keyword' => 'rice shortage',
            'volume' => 450,
            'triggers_alert' => false,
            'recorded_at' => $baseDate,
        ]);

        ExternalSignal::create([
            'region_id' => $ntt->id,
            'commodity_id' => $rice->id,
            'source_type' => 'social',
            'keyword' => 'rice price',
            'volume' => 320,
            'triggers_alert' => false,
            'recorded_at' => $baseDate,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Citizen Reports
        |--------------------------------------------------------------------------
        */

        $reportDescription =
            'Rice stock in our area is running low and prices '
            . 'have increased significantly.';

        foreach (
            [
                [$citizen1, '08:00:00'],
                [$citizen2, '09:00:00'],
                [$citizen3, '09:30:00'],
            ] as [$user, $time]
        ) {
            CitizenReport::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'region_id' => $ntt->id,
                    'commodity_id' => $rice->id,
                    'reported_at' => $baseDate
                        ->copy()
                        ->setTimeFromTimeString($time),
                ],
                [
                    'description' => $reportDescription,
                    'photo_path' => null,
                    'issue_type' => 'scarcity',
                    'urgency' => 'High',
                    'sentiment_score' => 0.88,
                    'is_verified' => true,
                    'eligible_for_risk' => true,
                    'verified_at' => $baseDate,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Risk Prediction
        |--------------------------------------------------------------------------
        */

        $prediction = RiskPrediction::updateOrCreate(
            [
                'region_id' => $ntt->id,
                'commodity_id' => $rice->id,
                'prediction_date' => $baseDate->toDateString(),
                'horizon_days' => 7,
            ],
            [
                'forecast_price_change_pct' => 18.75,
                'price_deviation_sd' => 1.80,
                'stock_coverage_days' => 5.00,
                'shipment_delay_days' => 4.00,
                'rainfall_anomaly_mm' => -100,
                'verified_report_volume' => 3,
                'risk_score' => 87,
                'risk_level' => 'High',
                'model_name' => 'XGBoost',
                'model_source' => 'prototype',
                'input_snapshot' => [
                    'forecast_price_change_pct' => 18.75,
                    'price_deviation_sd' => 1.80,
                    'stock_coverage_days' => 5.00,
                    'shipment_delay_days' => 4.00,
                    'rainfall_anomaly_mm' => -100,
                    'verified_report_volume' => 3,
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Risk Factors
        |--------------------------------------------------------------------------
        */

        $prediction->factors()->delete();

        $factors = [
            [
                'factor_code' => 'PURCHASE_SURGE',
                'factor_name' => 'Purchase Surge',
                'contribution_percentage' => 92,
                'raw_value' => 3,
                'description' => 'Verified community reports indicate increased demand pressure.',
            ],
            [
                'factor_code' => 'CROP_FAILURE',
                'factor_name' => 'Crop Failure',
                'contribution_percentage' => 80,
                'raw_value' => -100,
                'description' => 'Rainfall anomaly indicates potential production disruption.',
            ],
            [
                'factor_code' => 'DISTRIBUTION_DELAY',
                'factor_name' => 'Distribution Delay',
                'contribution_percentage' => 70,
                'raw_value' => 4,
                'description' => 'Historical shipment records indicate distribution delays.',
            ],
            [
                'factor_code' => 'PRICE_INCREASE',
                'factor_name' => 'Price Increase',
                'contribution_percentage' => 65,
                'raw_value' => 18.75,
                'description' => 'Forecast indicates a substantial increase in commodity price.',
            ],
        ];

        foreach ($factors as $factor) {
            $prediction->factors()->create($factor);
        }


        /*
        |--------------------------------------------------------------------------
        | Distribution Allocations
        |--------------------------------------------------------------------------
        */

        $allocation1 = DistributionAllocation::updateOrCreate(
            [
                'risk_prediction_id' => $prediction->id,
                'warehouse_id' => $makassarWarehouse->id,
            ],
            [
                'destination_region_id' => $ntt->id,
                'quantity_tons' => 5,
                'priority' => 'High',
                'route_distance_km' => 750,
                'estimated_transit_days' => 2,
                'status' => 'Recommended',
            ]
        );

        $allocation2 = DistributionAllocation::updateOrCreate(
            [
                'risk_prediction_id' => $prediction->id,
                'warehouse_id' => $surabayaWarehouse->id,
            ],
            [
                'destination_region_id' => $ntt->id,
                'quantity_tons' => 3,
                'priority' => 'Medium',
                'route_distance_km' => 1050,
                'estimated_transit_days' => 2,
                'status' => 'Recommended',
            ]
        );

        $allocation3 = DistributionAllocation::updateOrCreate(
            [
                'risk_prediction_id' => $prediction->id,
                'warehouse_id' => $localReserve->id,
            ],
            [
                'destination_region_id' => $ntt->id,
                'quantity_tons' => 2,
                'priority' => 'Emergency',
                'route_distance_km' => 0,
                'estimated_transit_days' => 1,
                'status' => 'Recommended',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Historical Shipment
        |--------------------------------------------------------------------------
        */

        $historicalShipment = Shipment::updateOrCreate(
            [
                'batch_id' => 'HIS-NTT-2026-001',
            ],
            [
                'distribution_allocation_id' => null,
                'commodity_id' => $rice->id,
                'origin_warehouse_id' => $makassarWarehouse->id,
                'destination_region_id' => $ntt->id,
                'sender_user_id' => $distributor->id,
                'receiver_user_id' => $government->id,
                'destination_name' => 'Regional Receiving Point',
                'quantity_tons' => 1,
                'status' => 'Received',
                'prepared_at' => $baseDate->copy()->subDays(5),
                'dispatched_at' => $baseDate->copy()->subDays(4),
                'arrived_at' => $baseDate,
                'sender_confirmed_at' => $baseDate->copy()->subDays(4),
                'receiver_confirmed_at' => $baseDate,
                'qr_token' => 'QR-HIS-NTT-001',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Current Shipment
        |--------------------------------------------------------------------------
        */

        $shipment = Shipment::updateOrCreate(
            [
                'batch_id' => 'BRS-NTT-2026-001',
            ],
            [
                'distribution_allocation_id' => $allocation1->id,
                'commodity_id' => $rice->id,
                'origin_warehouse_id' => $makassarWarehouse->id,
                'destination_region_id' => $ntt->id,
                'sender_user_id' => $distributor->id,
                'receiver_user_id' => null,
                'destination_name' => 'Priority Distribution Point',
                'quantity_tons' => 5,
                'status' => 'In Transit',
                'prepared_at' => $baseDate->copy()->subHours(2),
                'dispatched_at' => $baseDate
                    ->copy()
                    ->setTime(9, 42),
                'arrived_at' => null,
                'sender_confirmed_at' => $baseDate
                    ->copy()
                    ->setTime(9, 42),
                'receiver_confirmed_at' => null,
                'qr_token' => 'QR-BRS-NTT-2026-001',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Ledger
        |--------------------------------------------------------------------------
        */

        $ledgerPayload = [
            'batch_id' => $shipment->batch_id,
            'commodity' => $rice->name,
            'quantity_tons' => $shipment->quantity_tons,
            'origin' => $makassarWarehouse->name,
            'destination' => $ntt->name,
            'timestamp' => $shipment->dispatched_at
                ? $shipment->dispatched_at->toIso8601String()
                : null,
        ];

        $recordHash = hash(
            'sha256',
            json_encode(
                $ledgerPayload,
                JSON_UNESCAPED_SLASHES
                    | JSON_UNESCAPED_UNICODE
            )
        );

        LedgerTransaction::updateOrCreate(
            [
                'shipment_id' => $shipment->id,
            ],
            [
                'record_hash' => $recordHash,
                'blockchain_tx_hash' => null,
                'network' => 'polygon-amoy',
                'status' => 'recorded',
                'recorded_at' => $shipment->dispatched_at
                    ?? $baseDate,
                'metadata' => $ledgerPayload,
            ]
        );
    }
}
