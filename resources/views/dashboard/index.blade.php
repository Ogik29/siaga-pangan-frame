@extends('layouts.app')

@section('title', 'Dashboard - SiagaPangan')

@section('content')

<div class="sp-page-header">

    <h1 class="sp-page-title">
        Dashboard
    </h1>

    <p class="sp-page-subtitle">
        Monitor regional food risk, commodity conditions, demand trends,
        and community observations.
    </p>

</div>


{{-- =========================
     SUMMARY CARDS
========================= --}}

<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">
        <div class="sp-card">

            <div class="sp-stat-label">
                High-Risk Regions
            </div>

            <div class="sp-stat-value">
                {{ $highRiskRegions }}
            </div>

            <div class="sp-stat-description">
                Regions requiring closer attention
            </div>

        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="sp-card">

            <div class="sp-stat-label">
                Critical Commodity
            </div>

            <div class="sp-stat-value">
                {{ $criticalCommodity?->commodity?->name ?? 'No critical commodity' }}
            </div>

            <div class="sp-stat-description">
                Highest average predicted risk
            </div>

        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="sp-card">

            <div class="sp-stat-label">
                Community Reports
            </div>

            <div class="sp-stat-value">
                {{ $communityReports }}
            </div>

            <div class="sp-stat-description">
                Public observations received
            </div>

        </div>
    </div>


    <div class="col-xl-3 col-md-6">
        <div class="sp-card">

            <div class="sp-stat-label">
                Safe Stock Coverage
            </div>

            <div class="sp-stat-value">
                {{ $stockSafePercentage }}%
            </div>

            <div class="sp-stat-description">
                Coverage meeting the 7-day threshold
            </div>

        </div>
    </div>

</div>


{{-- =========================
     RISK MAP + RISK LIST
========================= --}}

<div class="row g-4 mb-4">

    <div class="col-lg-7">

        <div class="sp-card">

            <div class="sp-card-title">
                Regional Risk Overview
            </div>

            <div class="sp-map">

                <div class="sp-map-shape map-shape-1"></div>
                <div class="sp-map-shape map-shape-2"></div>
                <div class="sp-map-shape map-shape-3"></div>

                @php
                    $markerPositions = [
                        ['left' => '22%', 'top' => '25%'],
                        ['left' => '67%', 'top' => '27%'],
                        ['left' => '76%', 'top' => '60%'],
                        ['left' => '36%', 'top' => '65%'],
                        ['left' => '52%', 'top' => '45%'],
                    ];
                @endphp

                @foreach($riskList->take(5) as $index => $risk)

                    @php
                        $position = $markerPositions[$index % count($markerPositions)];

                        $level = strtolower((string) $risk->risk_level);

                        $markerClass = match($level) {
                            'high' => 'risk-high',
                            'medium' => 'risk-medium',
                            'low' => 'risk-low',
                            default => '',
                        };
                    @endphp

                    <div
                        class="map-marker {{ $markerClass }}"
                        style="
                            left: {{ $position['left'] }};
                            top: {{ $position['top'] }};
                        "
                    >
                        <span>
                            {{ round($risk->risk_score) }}
                        </span>

                        <span class="map-marker-label">
                            {{ $risk->region?->name ?? 'Unknown Region' }}
                        </span>
                    </div>

                @endforeach

                @if($riskList->isEmpty())

                    <div class="d-flex h-100 align-items-center justify-content-center">
                        <span class="text-muted">
                            No regional risk data available.
                        </span>
                    </div>

                @endif

            </div>

        </div>

    </div>


    <div class="col-lg-5">

        <div class="sp-card">

            <div class="sp-card-title">
                Risk List
            </div>

            <div class="table-responsive">

                <table class="sp-table">

                    <thead>
                        <tr>
                            <th>Region</th>
                            <th>Risk</th>
                            <th>Commodity</th>
                            <th>Horizon</th>
                        </tr>
                    </thead>

                    <tbody>

                    @forelse($riskList as $risk)

                        @php
                            $level = strtolower((string) $risk->risk_level);

                            $riskClass = match($level) {
                                'high' => 'risk-high',
                                'medium' => 'risk-medium',
                                'low' => 'risk-low',
                                default => '',
                            };
                        @endphp

                        <tr>

                            <td>
                                {{ $risk->region?->name ?? '-' }}
                            </td>

                            <td>
                                <span class="risk-badge {{ $riskClass }}">
                                    {{ $risk->risk_level }}
                                </span>
                            </td>

                            <td>
                                {{ $risk->commodity?->name ?? '-' }}
                            </td>

                            <td>
                                {{ $risk->horizon_days }} days
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No risk predictions available.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                <a
                    href="{{ route('analysis') }}"
                    class="sp-btn-outline d-block text-center"
                >
                    View Detailed Analysis
                </a>

            </div>

        </div>

    </div>

</div>


{{-- =========================
     CHARTS
========================= --}}

<div class="row g-4">

    <div class="col-lg-6">

        <div class="sp-card">

            <div class="sp-card-title">
                Commodity Price Trend
            </div>

            <div class="sp-card-subtitle mb-3">
                Recorded commodity prices over the latest available period.
            </div>

            <div class="chart-container">
                <canvas id="priceTrendChart"></canvas>
            </div>

        </div>

    </div>


    <div class="col-lg-6">

        <div class="sp-card">

            <div class="sp-card-title">
                Commodity Demand Trend
            </div>

            <div class="sp-card-subtitle mb-3">
                Average daily demand recorded in tons.
            </div>

            <div class="chart-container">
                <canvas id="demandTrendChart"></canvas>
            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

    const priceLabels = @json(
        $priceTrend
            ->pluck('price_date')
            ->values()
    );

    const priceValues = @json(
        $priceTrend
            ->pluck('price')
            ->map(fn ($value) => (float) $value)
            ->values()
    );


    const demandLabels = @json(
        $demandTrend
            ->pluck('record_date')
            ->values()
    );

    const demandValues = @json(
        $demandTrend
            ->pluck('average_daily_demand_tons')
            ->map(fn ($value) => (float) $value)
            ->values()
    );


    new Chart(
        document.getElementById('priceTrendChart'),
        {
            type: 'line',

            data: {
                labels: priceLabels,

                datasets: [
                    {
                        label: 'Recorded Price',
                        data: priceValues,
                        borderWidth: 3,
                        tension: 0.35,
                        fill: false
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        }
    );


    new Chart(
        document.getElementById('demandTrendChart'),
        {
            type: 'bar',

            data: {
                labels: demandLabels,

                datasets: [
                    {
                        label: 'Daily Demand (tons)',
                        data: demandValues,
                        borderWidth: 2
                    }
                ]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    );

</script>

@endpush