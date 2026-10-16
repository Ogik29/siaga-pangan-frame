@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="mb-4">

    <h1 class="fw-bold">
        Dashboard
    </h1>

    <p class="text-secondary">
        Monitor food security risks, commodity trends,
        and distribution conditions across Indonesia.
    </p>

</div>


{{-- STAT CARDS --}}

<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="sp-card p-4">

            <div class="small text-secondary">
                High-Risk Regions
            </div>

            <div class="fs-3 fw-bold">
                12 regions
            </div>

            <div class="small text-secondary">
                Increased from last week
            </div>

        </div>
    </div>


    <div class="col-md-3">
        <div class="sp-card p-4">

            <div class="small text-secondary">
                Critical Commodity
            </div>

            <div class="fs-3 fw-bold">
                Rice
            </div>

            <div class="small text-secondary">
                Requires daily monitoring
            </div>

        </div>
    </div>


    <div class="col-md-3">
        <div class="sp-card p-4">

            <div class="small text-secondary">
                Community Reports
            </div>

            <div class="fs-3 fw-bold">
                1,284 reports
            </div>

            <div class="small text-secondary">
                38% stock-related
            </div>

        </div>
    </div>


    <div class="col-md-3">
        <div class="sp-card p-4">

            <div class="small text-secondary">
                National Stock Coverage
            </div>

            <div class="fs-3 fw-bold">
                67%
            </div>

            <div class="small text-secondary">
                Regional redistribution required
            </div>

        </div>
    </div>

</div>


{{-- MAIN CONTENT --}}

<div class="row g-4">

    <div class="col-lg-8">

        <div class="sp-card p-4">

            <h2 class="sp-section-title">
                Regional Risk Indicators
            </h2>

            <div id="risk-map"
                 style="height: 350px;">
            </div>

        </div>

    </div>


    <div class="col-lg-4">

        <div class="sp-card p-4">

            <h2 class="sp-section-title">
                Regional Risk List
            </h2>

            {{-- table nanti --}}

        </div>

    </div>

</div>

@endsection