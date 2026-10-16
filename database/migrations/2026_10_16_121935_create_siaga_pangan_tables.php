<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Regions
        |--------------------------------------------------------------------------
        */

        Schema::create('regions', function (Blueprint $table) {
            $table->id();

            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('province');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Commodities
        |--------------------------------------------------------------------------
        */

        Schema::create('commodities', function (Blueprint $table) {
            $table->id();

            $table->string('code', 30)->unique();
            $table->string('name');

            $table->string('unit', 30)->default('ton');
            $table->string('category')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Warehouses
        |--------------------------------------------------------------------------
        */

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->string('code', 30)->unique();
            $table->string('name');

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->decimal('capacity_tons', 12, 2)
                ->default(0);

            $table->boolean('is_reserve')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Price Records
        |--------------------------------------------------------------------------
        */

        Schema::create('price_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->cascadeOnDelete();

            $table->date('price_date');

            $table->string('market_type', 30)
                ->default('traditional');

            $table->decimal('price', 15, 2);

            $table->decimal('previous_price', 15, 2)
                ->nullable();

            $table->decimal('change_pct', 8, 2)
                ->nullable();

            $table->string('source')
                ->nullable();

            $table->timestamps();

            $table->index([
                'region_id',
                'commodity_id',
                'price_date',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Price Forecasts
        |--------------------------------------------------------------------------
        */

        Schema::create('price_forecasts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->cascadeOnDelete();

            $table->date('forecast_date');

            $table->decimal('predicted_price', 15, 2);

            $table->decimal('lower_bound', 15, 2)
                ->nullable();

            $table->decimal('upper_bound', 15, 2)
                ->nullable();

            $table->unsignedInteger('horizon_days')
                ->default(7);

            $table->string('model_name')
                ->default('Prophet');

            $table->timestamp('generated_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'region_id',
                'commodity_id',
                'forecast_date',
                'model_name',
            ], 'pf_region_commodity_date_model_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Demand Records
        |--------------------------------------------------------------------------
        */

        Schema::create('demand_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->cascadeOnDelete();

            $table->date('record_date');

            $table->decimal(
                'average_daily_demand_tons',
                12,
                2
            );

            $table->string('source')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'region_id',
                'commodity_id',
                'record_date',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Stock Records
        |--------------------------------------------------------------------------
        */

        Schema::create('stock_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->cascadeOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->cascadeOnDelete();

            $table->date('record_date');

            $table->decimal(
                'quantity_tons',
                12,
                2
            );

            $table->decimal(
                'reserved_tons',
                12,
                2
            )->default(0);

            $table->string('source')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'warehouse_id',
                'commodity_id',
                'record_date',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Weather Records
        |--------------------------------------------------------------------------
        */

        Schema::create('weather_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->date('observed_date');

            $table->decimal(
                'rainfall_mm',
                10,
                2
            )->nullable();

            $table->decimal(
                'rainfall_anomaly_mm',
                10,
                2
            )->nullable();

            $table->string('source')
                ->default('BMKG');

            $table->timestamps();

            $table->unique([
                'region_id',
                'observed_date',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Market Indicators
        |--------------------------------------------------------------------------
        */

        Schema::create('market_indicators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->nullable()
                ->constrained('regions')
                ->nullOnDelete();

            $table->foreignId('commodity_id')
                ->nullable()
                ->constrained('commodities')
                ->nullOnDelete();

            $table->date('indicator_date');

            $table->decimal('yoy_pct', 8, 2)
                ->nullable();

            $table->decimal('mom_pct', 8, 2)
                ->nullable();

            $table->string('source')
                ->default('SP2KP');

            $table->timestamps();

            $table->index([
                'region_id',
                'commodity_id',
                'indicator_date',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | External Signals
        |--------------------------------------------------------------------------
        */

        Schema::create('external_signals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->nullable()
                ->constrained('regions')
                ->nullOnDelete();

            $table->foreignId('commodity_id')
                ->nullable()
                ->constrained('commodities')
                ->nullOnDelete();

            $table->string('source_type', 30);

            $table->string('keyword')
                ->nullable();

            $table->unsignedInteger('volume')
                ->default(0);

            $table->boolean('triggers_alert')
                ->default(false);

            $table->timestamp('recorded_at');

            $table->timestamps();

            $table->index([
                'source_type',
                'recorded_at',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Citizen Reports
        |--------------------------------------------------------------------------
        */

        Schema::create('citizen_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->cascadeOnDelete();

            $table->text('description');

            $table->string('photo_path')
                ->nullable();

            $table->string('issue_type', 30)
                ->nullable();

            $table->string('urgency', 20)
                ->default('Medium');

            $table->decimal(
                'sentiment_score',
                5,
                4
            )->nullable();

            $table->boolean('is_verified')
                ->default(false);

            $table->boolean('eligible_for_risk')
                ->default(false);

            $table->timestamp('reported_at');

            $table->timestamp('verified_at')
                ->nullable();

            $table->timestamps();

            $table->index([
                'region_id',
                'commodity_id',
                'reported_at',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Risk Predictions
        |--------------------------------------------------------------------------
        */

        Schema::create('risk_predictions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')
                ->constrained('regions')
                ->cascadeOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->cascadeOnDelete();

            $table->date('prediction_date');

            $table->unsignedInteger('horizon_days')
                ->default(7);

            $table->decimal(
                'forecast_price_change_pct',
                8,
                2
            )->default(0);

            $table->decimal(
                'price_deviation_sd',
                8,
                4
            )->default(0);

            $table->decimal(
                'stock_coverage_days',
                10,
                2
            )->default(0);

            $table->decimal(
                'shipment_delay_days',
                10,
                2
            )->default(0);

            $table->decimal(
                'rainfall_anomaly_mm',
                10,
                2
            )->default(0);

            $table->unsignedInteger(
                'verified_report_volume'
            )->default(0);

            $table->decimal(
                'risk_score',
                5,
                2
            );

            $table->string('risk_level', 20);

            $table->string('model_name')
                ->default('XGBoost');

            $table->string('model_source')
                ->default('fallback');

            $table->json('input_snapshot')
                ->nullable();

            $table->timestamp('alerted_at')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'region_id',
                'commodity_id',
                'prediction_date',
                'horizon_days',
            ], 'rp_region_commodity_date_horizon_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Risk Factors
        |--------------------------------------------------------------------------
        */

        Schema::create('risk_factors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('risk_prediction_id')
                ->constrained('risk_predictions')
                ->cascadeOnDelete();

            $table->string('factor_code', 50);
            $table->string('factor_name');

            $table->decimal(
                'contribution_percentage',
                6,
                2
            )->default(0);

            $table->decimal(
                'raw_value',
                12,
                4
            )->nullable();

            $table->text('description')
                ->nullable();

            $table->timestamps();

            $table->index('risk_prediction_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Distribution Allocations
        |--------------------------------------------------------------------------
        */

        Schema::create('distribution_allocations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('risk_prediction_id')
                ->constrained('risk_predictions')
                ->cascadeOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->foreignId('destination_region_id')
                ->constrained('regions')
                ->restrictOnDelete();

            $table->decimal(
                'quantity_tons',
                12,
                2
            );

            $table->string('priority', 20)
                ->default('Medium');

            $table->decimal(
                'route_distance_km',
                10,
                2
            )->nullable();

            $table->decimal(
                'estimated_transit_days',
                8,
                2
            )->nullable();

            $table->string('status', 30)
                ->default('Recommended');

            $table->timestamps();

            $table->index([
                'risk_prediction_id',
                'destination_region_id',
            ], 'da_risk_prediction_destination_region_index');
        });

        /*
        |--------------------------------------------------------------------------
        | Shipments
        |--------------------------------------------------------------------------
        */

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('distribution_allocation_id')
                ->nullable()
                ->constrained('distribution_allocations')
                ->nullOnDelete();

            $table->foreignId('commodity_id')
                ->constrained('commodities')
                ->restrictOnDelete();

            $table->foreignId('origin_warehouse_id')
                ->constrained('warehouses')
                ->restrictOnDelete();

            $table->foreignId('destination_region_id')
                ->constrained('regions')
                ->restrictOnDelete();

            $table->foreignId('sender_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('receiver_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('batch_id')
                ->unique();

            $table->string('destination_name')
                ->nullable();

            $table->decimal(
                'quantity_tons',
                12,
                2
            );

            $table->string('status', 30)
                ->default('Prepared');

            $table->timestamp('prepared_at')
                ->nullable();

            $table->timestamp('dispatched_at')
                ->nullable();

            $table->timestamp('arrived_at')
                ->nullable();

            $table->timestamp('distributed_at')
                ->nullable();

            $table->timestamp('sender_confirmed_at')
                ->nullable();

            $table->timestamp('receiver_confirmed_at')
                ->nullable();

            $table->string('qr_token', 100)
                ->nullable()
                ->unique();

            $table->timestamps();

            $table->index([
                'destination_region_id',
                'commodity_id',
                'status',
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Ledger Transactions
        |--------------------------------------------------------------------------
        */

        Schema::create('ledger_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shipment_id')
                ->unique()
                ->constrained('shipments')
                ->cascadeOnDelete();

            $table->char('record_hash', 64)
                ->unique();

            $table->string('blockchain_tx_hash')
                ->nullable();

            $table->string('network')
                ->default('polygon-amoy');

            $table->string('status', 30)
                ->default('recorded');

            $table->timestamp('recorded_at');

            $table->json('metadata')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_transactions');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('distribution_allocations');
        Schema::dropIfExists('risk_factors');
        Schema::dropIfExists('risk_predictions');
        Schema::dropIfExists('citizen_reports');
        Schema::dropIfExists('external_signals');
        Schema::dropIfExists('market_indicators');
        Schema::dropIfExists('weather_records');
        Schema::dropIfExists('stock_records');
        Schema::dropIfExists('demand_records');
        Schema::dropIfExists('price_forecasts');
        Schema::dropIfExists('price_records');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('commodities');
        Schema::dropIfExists('regions');
    }
};
