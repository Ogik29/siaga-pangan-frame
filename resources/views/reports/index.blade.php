@extends('layouts.app')

@section('title', 'Community Reports - SiagaPangan')

@section('content')

<div class="sp-page-header">

    <h1 class="sp-page-title">
        Community Reports
    </h1>

    <p class="sp-page-subtitle">
        Collect public observations and identify early food-supply signals.
    </p>

</div>


<div class="row g-4 mb-4">

    {{-- =========================
         REPORT FORM
    ========================= --}}

    <div class="col-lg-6">

        <div class="sp-card">

            <div class="sp-card-title">
                Submit a Community Report
            </div>

            <form
                action="{{ route('reports.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <div class="mb-3">

                    <label class="sp-label">
                        Region / Location
                    </label>

                    <select
                        name="region_id"
                        class="sp-select"
                        required
                    >

                        <option value="">
                            Select Region
                        </option>

                        @foreach($regions as $region)

                            <option
                                value="{{ $region->id }}"
                                {{ old('region_id') == $region->id ? 'selected' : '' }}
                            >
                                {{ $region->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label class="sp-label">
                        Commodity
                    </label>

                    <select
                        name="commodity_id"
                        class="sp-select"
                        required
                    >

                        <option value="">
                            Select Commodity
                        </option>

                        @foreach($commodities as $commodity)

                            <option
                                value="{{ $commodity->id }}"
                                {{ old('commodity_id') == $commodity->id ? 'selected' : '' }}
                            >
                                {{ $commodity->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-3">

                    <label class="sp-label">
                        Report Description
                    </label>

                    <textarea
                        name="description"
                        class="sp-textarea"
                        maxlength="5000"
                        placeholder="Describe the food-supply condition you observed..."
                        required
                    >{{ old('description') }}</textarea>

                    <div class="sp-help">
                        Reports may describe shortages, price increases,
                        distribution delays, or unusual stock conditions.
                    </div>

                </div>


                <div class="mb-4">

                    <label class="sp-label">
                        Supporting Photo
                    </label>

                    <input
                        type="file"
                        name="photo"
                        class="sp-input"
                        accept="image/*"
                    >

                    <div class="sp-help">
                        Optional. Image files up to 5 MB.
                    </div>

                </div>


                <button
                    type="submit"
                    class="sp-btn w-100"
                >
                    <i class="bi bi-send me-2"></i>
                    Submit Report
                </button>

            </form>

        </div>

    </div>


    {{-- =========================
         LATEST ANALYSIS
    ========================= --}}

    <div class="col-lg-6">

        <div class="sp-card">

            <div class="sp-card-title">
                Latest Report Analysis
            </div>

            @php
                $latestReport = $reports->first();
            @endphp

            @if($latestReport)

                @php

                    $sentiment = (float) (
                        $latestReport->sentiment_score ?? 0
                    );

                    $sentimentPercent = $sentiment <= 1
                        ? round($sentiment * 100)
                        : round($sentiment);

                    $issueLabel = ucwords(
                        str_replace(
                            '_',
                            ' ',
                            $latestReport->issue_type ?? 'Unknown'
                        )
                    );

                @endphp


                <div class="mb-3">

                    <div class="sp-stat-label">
                        Commodity
                    </div>

                    <div class="fw-semibold">
                        {{ $latestReport->commodity?->name ?? '-' }}
                    </div>

                </div>


                <div class="mb-3">

                    <div class="sp-stat-label">
                        Issue
                    </div>

                    <div class="fw-semibold">
                        {{ $issueLabel }}
                    </div>

                </div>


                <div class="mb-3">

                    <div class="sp-stat-label">
                        Urgency
                    </div>

                    <div>

                        <span
                            class="risk-badge
                            {{ strtolower($latestReport->urgency ?? '') === 'high'
                                ? 'risk-high'
                                : 'risk-medium' }}"
                        >
                            {{ $latestReport->urgency ?? 'Unknown' }}
                        </span>

                    </div>

                </div>


                <div class="mb-3">

                    <div class="sp-stat-label">
                        Sentiment
                    </div>

                    <div class="fw-semibold">
                        {{ $sentimentPercent }}%
                    </div>

                    <div class="sp-progress mt-2">

                        <div
                            class="sp-progress-bar"
                            style="width: {{ min(100, max(0, $sentimentPercent)) }}%"
                        ></div>

                    </div>

                </div>


                @php

                    $recommendation = match(
                        $latestReport->issue_type
                    ) {

                        'scarcity' =>
                            'Validate local stock availability and consider redistribution.',

                        'price_spike' =>
                            'Validate the reported price movement and monitor market conditions.',

                        'delay' =>
                            'Check shipment status and review the distribution route.',

                        'hoarding' =>
                            'Review stock concentration and prioritize controlled distribution.',

                        default =>
                            'Validate the report and check current local stock conditions.'

                    };

                @endphp


                <div class="mt-4">

                    <div class="sp-stat-label">
                        Recommended Action
                    </div>

                    <div class="report-description">
                        {{ $recommendation }}
                    </div>

                </div>

            @else

                <div class="sp-empty">

                    <div class="sp-empty-icon">
                        <i class="bi bi-chat-square-text"></i>
                    </div>

                    <h5>
                        No Reports Yet
                    </h5>

                    <p class="mb-0">
                        Community reports will be analyzed here
                        after they are submitted.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- =========================
     REPORT HISTORY
========================= --}}

<div class="sp-card">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div class="sp-card-title mb-0">
            Recent Community Reports
        </div>

        <span class="sp-card-subtitle">
            Latest {{ $reports->count() }} reports
        </span>

    </div>


    <div class="table-responsive">

        <table class="sp-table">

            <thead>

                <tr>

                    <th>Region</th>
                    <th>Commodity</th>
                    <th>Issue</th>
                    <th>Urgency</th>
                    <th>Sentiment</th>
                    <th>Verified</th>
                    <th>Reported At</th>

                </tr>

            </thead>

            <tbody>

            @forelse($reports as $report)

                @php

                    $sentiment = (float) (
                        $report->sentiment_score ?? 0
                    );

                    $sentimentPercent = $sentiment <= 1
                        ? round($sentiment * 100)
                        : round($sentiment);

                @endphp

                <tr>

                    <td>
                        {{ $report->region?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $report->commodity?->name ?? '-' }}
                    </td>

                    <td>
                        {{ ucwords(str_replace('_', ' ', $report->issue_type ?? '-')) }}
                    </td>

                    <td>

                        <span
                            class="risk-badge
                            {{ strtolower($report->urgency ?? '') === 'high'
                                ? 'risk-high'
                                : 'risk-medium' }}"
                        >
                            {{ $report->urgency ?? '-' }}
                        </span>

                    </td>

                    <td>
                        {{ $sentimentPercent }}%
                    </td>

                    <td>

                        @if($report->is_verified)

                            <span class="text-success fw-semibold">
                                Verified
                            </span>

                        @else

                            <span class="text-muted">
                                Pending
                            </span>

                        @endif

                    </td>

                    <td>
                        {{ optional($report->reported_at)->format('d M Y, H:i') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="text-center text-muted py-5"
                    >
                        No community reports available.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection