<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Commodity;
use App\Models\RiskPrediction;

use App\Services\RiskAnalysisService;
use App\Services\DistributionService;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnalysisController extends Controller
{
    public function __construct(
        private RiskAnalysisService $riskAnalysisService,
        private DistributionService $distributionService
    ) {}

    public function index(
        Request $request
    ): View {
        $regions =
            Region::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $commodities =
            Commodity::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $prediction = null;

        if (
            $request->filled('region_id') &&
            $request->filled('commodity_id')
        ) {
            $prediction =
                RiskPrediction::query()
                ->with([
                    'region',
                    'commodity',
                    'factors',
                    'allocations.warehouse',
                    'allocations.destinationRegion',
                ])
                ->where(
                    'region_id',
                    $request->integer(
                        'region_id'
                    )
                )
                ->where(
                    'commodity_id',
                    $request->integer(
                        'commodity_id'
                    )
                )
                ->where(
                    'horizon_days',
                    $request->integer(
                        'horizon_days',
                        7
                    )
                )
                ->latest('prediction_date')
                ->first();
        }

        return view(
            'analysis.index',
            compact(
                'regions',
                'commodities',
                'prediction'
            )
        );
    }


    public function analyze(
        Request $request
    ): RedirectResponse {
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

                'horizon_days' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:30',
                ],

                'required_tons' => [
                    'nullable',
                    'numeric',
                    'min:0.1',
                ],
            ]);

        $prediction =
            $this->riskAnalysisService
            ->analyze(
                $validated['region_id'],
                $validated['commodity_id'],
                $validated['horizon_days']
            );


        /*
        |--------------------------------------------------------------------------
        | Generate distribution recommendation
        |--------------------------------------------------------------------------
        */

        if (
            $prediction->risk_level === 'High'
        ) {
            $requiredTons =
                $validated['required_tons']
                ?? 10;

            $this->distributionService
                ->createForPrediction(
                    $prediction,
                    $requiredTons
                );
        }


        return redirect()
            ->route(
                'analysis',
                [
                    'region_id' =>
                    $validated['region_id'],

                    'commodity_id' =>
                    $validated['commodity_id'],

                    'horizon_days' =>
                    $validated['horizon_days'],
                ]
            )
            ->with(
                'success',
                'Risk analysis completed successfully.'
            );
    }
}
