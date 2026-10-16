@extends('layouts.app')

@section('title', 'Risk Analysis - SiagaPangan')

@section('content')

@php

    $selectedRegionId = old(
        'region_id',
        $prediction?->region_id ?? $regions->first()?->id
    );

    $selectedCommodityId = old(
        'commodity_id',
        $prediction?->commodity_id ?? $commodities->first()?->id
    );

    $selectedHorizon = old(
        'horizon_days',
        $prediction?->horizon_days ?? 7
    );

@endphp


<div class="sp-page-header">

    <h1 class="sp-page-title">
        Risk Analysis & Distribution
    </h1>

    <p class="sp-page-subtitle">
        Analyze regional food risk and generate stock distribution recommendations.
    </p>

</div>


{{-- =========================
     ANALYSIS FILTER
========================= --}}

<div class="sp-card mb-4">

    <form
        action="{{ route('analysis.run') }}"
        method="POST"
    >

        @csrf

        <div class="row g-3 align-items-end">

            <div class="col-lg-3">

                <label class="sp-label">
                    Region
                </label>

                <select
                    name="region_id"
                    class="sp-select"
                    required
                >

                    @foreach($regions as $region)

                        <option
                            value="{{ $region->id }}"
                            {{ (string) $selectedRegionId === (string) $region->id ? 'selected' : '' }}
                        >
                            {{ $region->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-lg-3">

                <label class="sp-label">
                    Commodity
                </label>

                <select
                    name="commodity_id"
                    class="sp-select"
                    required
                >

                    @foreach($commodities as $commodity)

                        <option
                            value="{{ $commodity->id }}"
                            {{ (string) $selectedCommodityId === (string) $commodity->id ? 'selected' : '' }}
                        >
                            {{ $commodity->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            <div class="col-lg-3">

                <label class="sp-label">
                    Prediction Horizon
                </label>

                <select
                    name="horizon_days"
                    class="sp-select"
                    required
                >

                    <option
                        value="7"
                        {{ $selectedHorizon == 7 ? 'selected' : '' }}
                    >
                        7 days
                    </option>

                    <option
                        value="14"
                        {{ $selectedHorizon == 14 ? 'selected' : '' }}
                    >
                        14 days
                    </option>

                    <option
                        value="30"
                        {{ $selectedHorizon == 30 ? 'selected' : '' }}
                    >
                        30 days
                    </option>

                </select>

            </div>


            <div class="col-lg-3">

                <input
                    type="hidden"
                    name="required_tons"
                    value="10"
                >

                <button
                    type="submit"
                    class="sp-btn w-100"
                >
                    <i class="bi bi-cpu me-2"></i>
                    Analyze Risk
                </button>

            </div>

        </div>

    </form>

</div>


{{-- =========================
     EMPTY STATE
========================= --}}

@if(!$prediction)

    <div class="sp-card">

        <div class="sp-empty">

            <div class="sp-empty-icon">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <h5>
                No Risk Analysis Yet
            </h5>

            <p class="mb-0">
                Select a region, commodity, and prediction horizon,
                then run the risk analysis.
            </p>

        </div>

    </div>

@endif


@if($prediction)

    @php

        $riskLevel = strtolower(
            trim((string) $prediction->risk_level)
        );

        $riskClass = match($riskLevel) {
            'high' => 'risk-high',
            'medium' => 'risk-medium',
            'low' => 'risk-low',
            default => '',
        };

        $totalAllocated = $prediction->allocations->sum('quantity_tons');

    @endphp


    {{-- =========================
         PREDICTION SUMMARY
    ========================= --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-4">

            <div class="sp-card">

                <div class="sp-card-title">
                    Risk Score
                </div>

                <div class="risk-score-wrapper">

                    <div class="risk-score-circle">

                        <div class="risk-score-number">
                            {{ round($prediction->risk_score) }}
                        </div>

                        <div class="risk-score-label">
                            {{ $prediction->risk_level }} Risk
                        </div>

                    </div>

                </div>

                <div class="text-center text-muted small">

                    Potential food shortage within
                    {{ $prediction->horizon_days }} days.

                </div>

            </div>

        </div>


        {{-- FACTORS --}}

        <div class="col-lg-4">

            <div class="sp-card">

                <div class="sp-card-title">
                    Risk Factors
                </div>

                @forelse($prediction->factors as $factor)

                    @php
                        $factorValue = max(
                            0,
                            min(
                                100,
                                (float) $factor->contribution_percentage
                            )
                        );
                    @endphp

                    <div class="factor-item">

                        <div class="factor-header">

                            <span class="factor-name">
                                {{ $factor->factor_name }}
                            </span>

                            <span class="factor-value">
                                {{ round($factorValue) }}%
                            </span>

                        </div>

                        <div class="sp-progress">

                            <div
                                class="sp-progress-bar"
                                style="width: {{ $factorValue }}%"
                            ></div>

                        </div>

                    </div>

                @empty

                    <div class="text-muted">
                        No risk factor details available.
                    </div>

                @endforelse

            </div>

        </div>


        {{-- STRATEGY --}}

        <div class="col-lg-4">

            <div class="sp-card">

                <div class="sp-card-title">
                    Suggested Mitigation Actions
                </div>

                <ol class="mb-0 ps-3">

                    <li class="mb-3">
                        Increase the regional stock buffer.
                    </li>

                    <li class="mb-3">
                        Open the nearest available reserve source.
                    </li>

                    <li class="mb-3">
                        Prioritize vulnerable destinations.
                    </li>

                    <li>
                        Monitor price and demand signals regularly.
                    </li>

                </ol>

            </div>

        </div>

    </div>


    {{-- =========================
         PREDICTION DETAILS
    ========================= --}}

    <div class="sp-card mb-4">

        <div class="sp-card-title">
            Prediction Details
        </div>

        <div class="row g-3">

            <div class="col-md-3">

                <div class="sp-stat-label">
                    Region
                </div>

                <div class="fw-semibold">
                    {{ $prediction->region?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-3">

                <div class="sp-stat-label">
                    Commodity
                </div>

                <div class="fw-semibold">
                    {{ $prediction->commodity?->name ?? '-' }}
                </div>

            </div>


            <div class="col-md-3">

                <div class="sp-stat-label">
                    Prediction Date
                </div>

                <div class="fw-semibold">
                    {{ optional($prediction->prediction_date)->format('d M Y') }}
                </div>

            </div>


            <div class="col-md-3">

                <div class="sp-stat-label">
                    Risk Level
                </div>

                <div>
                    <span class="risk-badge {{ $riskClass }}">
                        {{ $prediction->risk_level }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================
         DISTRIBUTION ROUTE
    ========================= --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-7">

            <div class="sp-card">

                <div class="sp-card-title">
                    Recommended Distribution Route
                </div>

                <div class="sp-map">

                    @forelse($prediction->allocations as $index => $allocation)

                        @php

                            $routePositions = [
                                ['left' => '15%', 'top' => '25%'],
                                ['left' => '43%', 'top' => '48%'],
                                ['left' => '72%', 'top' => '65%'],
                            ];

                            $position = $routePositions[
                                $index % count($routePositions)
                            ];

                        @endphp

                        <div
                            class="map-marker"
                            style="
                                left: {{ $position['left'] }};
                                top: {{ $position['top'] }};
                            "
                        >

                            <span>
                                {{ round($allocation->quantity_tons) }}
                            </span>

                            <span class="map-marker-label">

                                {{ $allocation->warehouse?->name ?? 'Warehouse' }}

                                →
                                {{ $allocation->destinationRegion?->name ?? 'Destination' }}

                            </span>

                        </div>

                    @empty

                        <div class="d-flex h-100 align-items-center justify-content-center">

                            <span class="text-muted">
                                No distribution route available.
                            </span>

                        </div>

                    @endforelse

                </div>

                <div class="sp-help mt-3">

                    Route recommendations consider available stock,
                    destination risk, distance, and estimated transit time.

                </div>

            </div>

        </div>


        {{-- =========================
             ALLOCATION TABLE
        ========================= --}}

        <div class="col-lg-5">

            <div class="sp-card">

                <div class="sp-card-title">
                    Stock Allocation Recommendation
                </div>

                <div class="table-responsive">

                    <table class="sp-table">

                        <thead>

                            <tr>
                                <th>Source</th>
                                <th>Quantity</th>
                                <th>Priority</th>
                            </tr>

                        </thead>

                        <tbody>

                        @forelse($prediction->allocations as $allocation)

                            <tr>

                                <td>
                                    {{ $allocation->warehouse?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format($allocation->quantity_tons, 1) }}
                                    tons
                                </td>

                                <td>

                                    <span class="risk-badge">

                                        {{ $allocation->priority }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="text-center text-muted py-4"
                                >
                                    No allocation recommendation available.
                                </td>

                            </tr>

                        @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3 pt-3 border-top">

                    <div class="d-flex justify-content-between">

                        <span class="fw-semibold">
                            Total Allocation
                        </span>

                        <span class="fw-bold">
                            {{ number_format($totalAllocated, 1) }} tons
                        </span>

                    </div>

                </div>


                <form
                    action="{{ route('distribution.send', $prediction) }}"
                    method="POST"
                    class="mt-3"
                >

                    @csrf

                    <button
                        type="submit"
                        class="sp-btn w-100"
                    >
                        <i class="bi bi-send me-2"></i>
                        Send Recommendation to Distributor
                    </button>

                </form>

            </div>

        </div>

    </div>


    {{-- =========================
         ALLOCATION DETAILS
    ========================= --}}

    <div class="sp-card">

        <div class="sp-card-title">
            Distribution Details
        </div>

        <div class="table-responsive">

            <table class="sp-table">

                <thead>

                    <tr>
                        <th>Source</th>
                        <th>Destination</th>
                        <th>Quantity</th>
                        <th>Distance</th>
                        <th>Transit</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>

                @forelse($prediction->allocations as $allocation)

                    @php
                        $shipment = $allocation->shipments->first();
                    @endphp

                    <tr>

                        <td>
                            {{ $allocation->warehouse?->name ?? '-' }}
                        </td>

                        <td>
                            {{ $allocation->destinationRegion?->name ?? '-' }}
                        </td>

                        <td>
                            {{ number_format($allocation->quantity_tons, 1) }}
                            tons
                        </td>

                        <td>
                            {{ $allocation->route_distance_km ?? '-' }}
                            km
                        </td>

                        <td>
                            {{ $allocation->estimated_transit_days ?? '-' }}
                            days
                        </td>

                        <td>
                            <span class="risk-badge">
                                {{ $allocation->priority }}
                            </span>
                        </td>

                        <td>
                            {{ $allocation->status }}
                        </td>

                        <td>

                            @if($shipment)

                                <a
                                    href="{{ route('shipments.show', $shipment) }}"
                                    class="sp-btn-outline"
                                >
                                    Track
                                </a>

                            @else

                                <span class="text-muted small">
                                    Not created
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center text-muted py-4"
                        >
                            No distribution data available.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endif

@endsection