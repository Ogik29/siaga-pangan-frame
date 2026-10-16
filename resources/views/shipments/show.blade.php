@extends('layouts.app')

@section('title', 'Shipment Tracking - SiagaPangan')

@section('content')

@php

    $statusOrder = [
        'Prepared' => 1,
        'In Transit' => 2,
        'Received' => 3,
        'Distributed' => 4,
    ];

    $currentStep = $statusOrder[$shipment->status] ?? 1;

    $shipmentSteps = [
        [
            'number' => 1,
            'title' => 'Stock Prepared',
            'date' => $shipment->prepared_at,
        ],
        [
            'number' => 2,
            'title' => 'In Transit',
            'date' => $shipment->dispatched_at,
        ],
        [
            'number' => 3,
            'title' => 'Received',
            'date' => $shipment->arrived_at,
        ],
        [
            'number' => 4,
            'title' => 'Distributed',
            'date' => $shipment->distributed_at,
        ],
    ];

@endphp


<div class="sp-page-header">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

        <div>

            <h1 class="sp-page-title">
                Shipment Tracking
            </h1>

            <p class="sp-page-subtitle">
                Monitor food assistance movement from source to destination.
            </p>

        </div>


        <a
            href="{{ route('analysis') }}"
            class="sp-btn-outline"
        >
            <i class="bi bi-arrow-left me-2"></i>
            Back to Analysis
        </a>

    </div>

</div>


<div class="row g-4">

    {{-- =========================
         SHIPMENT SUMMARY
    ========================= --}}

    <div class="col-lg-7">

        <div class="sp-card mb-4">

            <div class="d-flex justify-content-between align-items-start mb-4">

                <div>

                    <div class="sp-card-title mb-1">
                        Shipment Details
                    </div>

                    <div class="sp-card-subtitle">
                        Batch ID: {{ $shipment->batch_id }}
                    </div>

                </div>


                <span class="risk-badge">
                    {{ $shipment->status }}
                </span>

            </div>


            <div class="row g-4">

                <div class="col-md-6">

                    <div class="sp-stat-label">
                        Commodity
                    </div>

                    <div class="fw-semibold">
                        {{ $shipment->commodity?->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="sp-stat-label">
                        Quantity
                    </div>

                    <div class="fw-semibold">
                        {{ number_format($shipment->quantity_tons, 1) }}
                        tons
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="sp-stat-label">
                        Origin
                    </div>

                    <div class="fw-semibold">
                        {{ $shipment->originWarehouse?->name ?? '-' }}
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="sp-stat-label">
                        Destination
                    </div>

                    <div class="fw-semibold">
                        {{ $shipment->destinationRegion?->name ?? '-' }}
                    </div>

                </div>

            </div>

        </div>


        {{-- =========================
             TIMELINE
        ========================= --}}

        <div class="sp-card">

            <div class="sp-card-title">
                Shipment Progress
            </div>

            <div class="shipment-timeline">

                @foreach($shipmentSteps as $step)

                    @php
                        $completed = $currentStep >= $step['number'];
                    @endphp

                    <div class="shipment-step">

                        <div
                            class="shipment-dot
                            {{ $completed ? 'completed' : '' }}"
                        >
                        </div>


                        <div class="shipment-step-title">

                            {{ $step['title'] }}

                        </div>


                        <div class="shipment-step-date">

                            @if($step['date'])

                                {{ \Illuminate\Support\Carbon::parse(
                                    $step['date']
                                )->format('d M Y, H:i') }}

                            @else

                                Waiting for update

                            @endif

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- =========================
         LEDGER
    ========================= --}}

    <div class="col-lg-5">

        <div class="sp-card mb-4">

            <div class="sp-card-title">
                Transparent Ledger
            </div>

            <p class="sp-card-subtitle mb-4">
                Shipment movements are recorded as traceable transactions.
            </p>


            @if($shipment->ledgerTransaction)

                <div class="ledger-field">

                    <div class="ledger-label">
                        Record Hash
                    </div>

                    <div class="ledger-value">
                        {{ $shipment->ledgerTransaction->record_hash ?? '-' }}
                    </div>

                </div>


                <div class="ledger-field">

                    <div class="ledger-label">
                        Blockchain Transaction Hash
                    </div>

                    <div class="ledger-value">
                        {{ $shipment->ledgerTransaction->blockchain_tx_hash ?? '-' }}
                    </div>

                </div>


                <div class="ledger-field">

                    <div class="ledger-label">
                        Network
                    </div>

                    <div class="ledger-value">
                        {{ $shipment->ledgerTransaction->network ?? '-' }}
                    </div>

                </div>


                <div class="ledger-field">

                    <div class="ledger-label">
                        Status
                    </div>

                    <div class="ledger-value">
                        {{ $shipment->ledgerTransaction->status ?? '-' }}
                    </div>

                </div>


                <div class="ledger-field">

                    <div class="ledger-label">
                        Recorded At
                    </div>

                    <div class="ledger-value">

                        {{ optional(
                            $shipment->ledgerTransaction->recorded_at
                        )->format('d M Y, H:i') }}

                    </div>

                </div>

            @else

                <div class="sp-empty py-4">

                    <i class="bi bi-link-45deg sp-empty-icon"></i>

                    <p class="mb-0">
                        Ledger transaction is not available yet.
                    </p>

                </div>

            @endif

        </div>


        {{-- =========================
             QR
        ========================= --}}

        <div class="sp-card">

            <div class="sp-card-title">
                Shipment QR
            </div>

            <div class="d-flex align-items-center gap-4">

                <div class="qr-box">

                    <div class="qr-inner">
                        QR
                    </div>

                </div>


                <div>

                    <div class="sp-stat-label">
                        Tracking Token
                    </div>

                    <div class="fw-semibold small text-break">
                        {{ $shipment->qr_token ?? 'Not available' }}
                    </div>

                    <div class="sp-help">
                        Use this token to verify the shipment record.
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================
     CONFIRMATION ACTIONS
========================= --}}

<div class="sp-card mt-4">

    <div class="sp-card-title">
        Shipment Actions
    </div>

    <div class="row g-3">

        @if($shipment->status === 'Prepared')

            <div class="col-md-6">

                <form
                    action="{{ route(
                        'shipments.confirm.sender',
                        $shipment
                    ) }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="sp-btn w-100"
                    >
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Confirm Dispatch
                    </button>

                </form>

            </div>

        @endif


        @if($shipment->status === 'In Transit')

            <div class="col-md-6">

                <form
                    action="{{ route(
                        'shipments.confirm.receiver',
                        $shipment
                    ) }}"
                    method="POST"
                >

                    @csrf

                    <button
                        type="submit"
                        class="sp-btn w-100"
                    >
                        <i class="bi bi-box-arrow-in-down me-2"></i>
                        Confirm Receipt
                    </button>

                </form>

            </div>

        @endif


        @if(
            $shipment->status === 'Received' ||
            $shipment->status === 'Distributed'
        )

            <div class="col-12">

                <div class="sp-alert">

                    <i class="bi bi-check-circle me-2"></i>

                    This shipment has been received and recorded
                    in the tracking ledger.

                </div>

            </div>

        @endif

    </div>

</div>

@endsection