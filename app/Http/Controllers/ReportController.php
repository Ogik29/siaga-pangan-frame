<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCitizenReportRequest;
use App\Models\CitizenReport;
use App\Models\Commodity;
use App\Models\PriceRecord;
use App\Models\Region;
use App\Services\ReportAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private ReportAnalysisService $reportAnalysisService
    ) {}

    public function index(): View
    {
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

        $reports =
            CitizenReport::query()
                ->with([
                    'region',
                    'commodity',
                    'user',
                ])
                ->latest('reported_at')
                ->limit(20)
                ->get();

        return view(
            'reports.index',
            compact(
                'regions',
                'commodities',
                'reports'
            )
        );
    }

    public function store(
        StoreCitizenReportRequest $request
    ): RedirectResponse {
        $validated =
            $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Upload photo
        |--------------------------------------------------------------------------
        */

        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath =
                $request
                    ->file('photo')
                    ->store(
                        'reports',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | NLP Analysis
        |--------------------------------------------------------------------------
        */

        $analysis =
            $this->reportAnalysisService
                ->analyze(
                    $validated['description']
                );

        /*
        |--------------------------------------------------------------------------
        | Save report
        |--------------------------------------------------------------------------
        */

        $report =
            CitizenReport::create([
                'user_id' => Auth::id(),

                'region_id' => $validated['region_id'],

                'commodity_id' => $validated['commodity_id'],

                'description' => $validated['description'],

                'photo_path' => $photoPath,

                'issue_type' => $analysis['issue_type'],

                'urgency' => $analysis['urgency'],

                'sentiment_score' => $analysis['sentiment_score'],

                'is_verified' => false,

                'eligible_for_risk' => false,

                'reported_at' => now(),
            ]);

        /*
        |--------------------------------------------------------------------------
        | Credibility Rule
        |--------------------------------------------------------------------------
        */

        $this->verifyReportEligibility(
            $report
        );

        return redirect()
            ->route('reports')
            ->with(
                'success',
                'Your report has been submitted successfully.'
            );
    }

    private function verifyReportEligibility(
        CitizenReport $report
    ): void {
        $start =
            $report->reported_at
                ->copy()
                ->subDay();

        $independentReports =
            CitizenReport::query()
                ->where(
                    'region_id',
                    $report->region_id
                )
                ->where(
                    'commodity_id',
                    $report->commodity_id
                )
                ->whereBetween(
                    'reported_at',
                    [
                        $start,
                        $report->reported_at,
                    ]
                )
                ->whereNotNull('user_id')
                ->where(
                    'id',
                    '!=',
                    $report->id
                )
                ->distinct('user_id')
                ->count('user_id');

        /*
        |--------------------------------------------------------------------------
        | Price anomaly
        |--------------------------------------------------------------------------
        */

        $latestDate =
            PriceRecord::query()
                ->where(
                    'region_id',
                    $report->region_id
                )
                ->where(
                    'commodity_id',
                    $report->commodity_id
                )
                ->max('price_date');

        $priceAnomaly = false;

        if ($latestDate) {
            $currentPrice =
                PriceRecord::query()
                    ->where(
                        'region_id',
                        $report->region_id
                    )
                    ->where(
                        'commodity_id',
                        $report->commodity_id
                    )
                    ->whereDate(
                        'price_date',
                        $latestDate
                    )
                    ->avg('price');

            $nationalAverage =
                PriceRecord::query()
                    ->where(
                        'commodity_id',
                        $report->commodity_id
                    )
                    ->whereDate(
                        'price_date',
                        $latestDate
                    )
                    ->avg('price');

            if (
                $nationalAverage > 0 &&
                $currentPrice >
                ($nationalAverage * 1.10)
            ) {
                $priceAnomaly = true;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Rule:
        | 3 independent reports within 24 hours
        | OR 1 report + price anomaly
        |--------------------------------------------------------------------------
        */

        $totalIndependent =
            $independentReports
            + (
                $report->user_id
                ? 1
                : 0
            );

        if (
            $totalIndependent >= 3
            || $priceAnomaly
        ) {
            $report->update([
                'is_verified' => true,

                'eligible_for_risk' => true,

                'verified_at' => now(),
            ]);
        }
    }
}
