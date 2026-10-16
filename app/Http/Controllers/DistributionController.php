<?php

namespace App\Http\Controllers;

use App\Models\RiskPrediction;
use App\Models\DistributionAllocation;
use App\Models\Shipment;

use App\Services\DistributionService;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    public function __construct(
        private DistributionService $distributionService
    ) {}

    public function recommend(
        Request $request
    ) {
        $validated =
            $request->validate([
                'region_id' => [
                    'required',
                    'exists:regions,id',
                ],

                'commodity_id' => [
                    'required',
                    'exists:commodities,id',
                ],

                'required_tons' => [
                    'required',
                    'numeric',
                    'min:0.1',
                ],
            ]);

        $recommendations =
            $this->distributionService
            ->recommend(
                $validated['region_id'],
                $validated['commodity_id'],
                $validated['required_tons']
            );

        return response()->json([
            'success' => true,
            'data' => $recommendations,
        ]);
    }


    public function sendRecommendation(
        RiskPrediction $prediction
    ): RedirectResponse {
        $prediction->load([
            'region',
            'commodity',
            'allocations',
        ]);

        DB::transaction(function () use (
            $prediction
        ) {
            foreach (
                $prediction->allocations
                as $allocation
            ) {
                if (
                    $allocation->shipments()->exists()
                ) {
                    continue;
                }

                $batchId =
                    'BRS-'
                    . $prediction->region->code
                    . '-'
                    . now()->format('Y')
                    . '-'
                    . str_pad(
                        (string) $allocation->id,
                        3,
                        '0',
                        STR_PAD_LEFT
                    );

                Shipment::create([
                    'distribution_allocation_id' =>
                    $allocation->id,

                    'commodity_id' =>
                    $prediction->commodity_id,

                    'origin_warehouse_id' =>
                    $allocation->warehouse_id,

                    'destination_region_id' =>
                    $allocation->destination_region_id,

                    'sender_user_id' =>
                    auth()->id(),

                    'receiver_user_id' =>
                    null,

                    'batch_id' =>
                    $batchId,

                    'destination_name' =>
                    'Regional Receiving Point',

                    'quantity_tons' =>
                    $allocation->quantity_tons,

                    'status' =>
                    'Prepared',

                    'prepared_at' =>
                    now(),

                    'qr_token' =>
                    'QR-' . strtoupper(
                        bin2hex(
                            random_bytes(8)
                        )
                    ),
                ]);

                $allocation->update([
                    'status' =>
                    'Shipment Created',
                ]);
            }
        });

        return redirect()
            ->route(
                'analysis',
                [
                    'region_id' =>
                    $prediction->region_id,

                    'commodity_id' =>
                    $prediction->commodity_id,

                    'horizon_days' =>
                    $prediction->horizon_days,
                ]
            )
            ->with(
                'success',
                'Distribution recommendation sent to the distributor.'
            );
    }
}
