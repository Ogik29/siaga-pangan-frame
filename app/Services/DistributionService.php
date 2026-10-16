<?php
// Konsepnya sesuai dengan paper: ketika wilayah masuk High risk, sistem mengusulkan alokasi stok dari sumber terdekat.

namespace App\Services;

use App\Models\Region;
use App\Models\Warehouse;
use App\Models\StockRecord;
use App\Models\RiskPrediction;
use App\Models\DistributionAllocation;

use Illuminate\Support\Collection;

class DistributionService
{
    public function recommend(
        int $destinationRegionId,
        int $commodityId,
        float $requiredTons
    ): Collection {
        $destination =
            Region::findOrFail(
                $destinationRegionId
            );

        $warehouses =
            Warehouse::query()
            ->where('is_active', true)
            ->with('region')
            ->get()
            ->sortBy([
                ['is_reserve', 'asc'],
                ['id', 'asc'],
            ]);

        $recommendations = collect();

        $remaining = $requiredTons;

        foreach ($warehouses as $warehouse) {
            if ($remaining <= 0) {
                break;
            }

            $stock = StockRecord::query()
                ->where(
                    'warehouse_id',
                    $warehouse->id
                )
                ->where(
                    'commodity_id',
                    $commodityId
                )
                ->orderByDesc('record_date')
                ->first();

            if (!$stock) {
                continue;
            }

            $available =
                max(
                    0,
                    $stock->quantity_tons
                        - $stock->reserved_tons
                );

            if ($available <= 0) {
                continue;
            }

            $quantity =
                min(
                    $available,
                    $remaining
                );

            $distance =
                $this->distanceKm(
                    $warehouse->latitude,
                    $warehouse->longitude,
                    $destination->latitude,
                    $destination->longitude
                );

            $priority = $warehouse->is_reserve
                ? 'Emergency'
                : (
                    $recommendations->isEmpty()
                    ? 'High'
                    : 'Medium'
                );

            $estimatedDays =
                max(
                    1,
                    ceil($distance / 600)
                );

            $recommendations->push([
                'warehouse_id' =>
                $warehouse->id,

                'warehouse_name' =>
                $warehouse->name,

                'destination_region_id' =>
                $destination->id,

                'quantity_tons' =>
                round($quantity, 2),

                'priority' =>
                $priority,

                'route_distance_km' =>
                round($distance, 2),

                'estimated_transit_days' =>
                $estimatedDays,
            ]);

            $remaining -= $quantity;
        }

        return $recommendations;
    }


    public function createForPrediction(
        RiskPrediction $prediction,
        float $requiredTons = 10
    ): Collection {
        $prediction->load(
            'region',
            'commodity'
        );

        DistributionAllocation::query()
            ->where(
                'risk_prediction_id',
                $prediction->id
            )
            ->delete();

        $recommendations =
            $this->recommend(
                $prediction->region_id,
                $prediction->commodity_id,
                $requiredTons
            );

        foreach ($recommendations as $recommendation) {
            DistributionAllocation::create([
                'risk_prediction_id' =>
                $prediction->id,

                'warehouse_id' =>
                $recommendation['warehouse_id'],

                'destination_region_id' =>
                $recommendation['destination_region_id'],

                'quantity_tons' =>
                $recommendation['quantity_tons'],

                'priority' =>
                $recommendation['priority'],

                'route_distance_km' =>
                $recommendation['route_distance_km'],

                'estimated_transit_days' =>
                $recommendation['estimated_transit_days'],

                'status' =>
                'Recommended',
            ]);
        }

        return $prediction
            ->allocations()
            ->with([
                'warehouse',
                'destinationRegion',
            ])
            ->get();
    }


    private function distanceKm(
        ?float $lat1,
        ?float $lon1,
        ?float $lat2,
        ?float $lon2
    ): float {
        if (
            $lat1 === null ||
            $lon1 === null ||
            $lat2 === null ||
            $lon2 === null
        ) {
            return 0;
        }

        $earthRadius = 6371;

        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);

        $latDifference =
            deg2rad($lat2 - $lat1);

        $lonDifference =
            deg2rad($lon2 - $lon1);

        $a =
            sin($latDifference / 2) ** 2
            +
            cos($lat1)
            * cos($lat2)
            * sin($lonDifference / 2) ** 2;

        $c =
            2 * atan2(
                sqrt($a),
                sqrt(1 - $a)
            );

        return $earthRadius * $c;
    }
}
