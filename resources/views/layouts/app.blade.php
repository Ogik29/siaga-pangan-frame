<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'SiagaPangan')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet"
    >

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sp-dark: #121a2a;
            --sp-text: #182033;
            --sp-muted: #697386;
            --sp-border: #a8b1c0;
            --sp-soft: #f3f5f8;
            --sp-white: #ffffff;
            --sp-high: #c94c4c;
            --sp-medium: #c58a28;
            --sp-low: #4d8a68;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f9fb;
            color: var(--sp-text);
            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
            font-size: 14px;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* =========================
           TOP NAVBAR
        ========================= */

        .sp-navbar {
            height: 80px;
            background: var(--sp-white);
            border-bottom: 1px solid #d1d6df;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .sp-brand {
            display: flex;
            align-items: center;
            gap: 13px;
            font-size: 23px;
            font-weight: 700;
            color: var(--sp-text);
        }

        .sp-brand-icon {
            width: 39px;
            height: 39px;
            border: 2px solid #788396;
            border-radius: 50%;
            background: #eef0f3;
        }

        .sp-top-menu {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sp-top-link {
            padding: 11px 20px;
            border-radius: 30px;
            font-weight: 600;
            transition: .2s;
        }

        .sp-top-link:hover {
            background: #eef0f3;
        }

        .sp-top-link.active {
            background: var(--sp-dark);
            color: white;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sp-sidebar {
            position: fixed;
            top: 80px;
            left: 0;
            bottom: 0;
            width: 260px;
            background: white;
            border-right: 1px solid #d1d6df;
            padding: 30px 28px;
            z-index: 900;
        }

        .sp-side-menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sp-side-link {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 10px;
            border-radius: 13px;
            font-weight: 500;
            transition: .2s;
        }

        .sp-side-link:hover {
            background: #f0f2f5;
        }

        .sp-side-link.active {
            background: #e8ebef;
            border: 2px solid #aab3c1;
        }

        .sp-number {
            width: 26px;
            height: 26px;
            border: 2px solid #778296;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .sp-purpose {
            position: absolute;
            left: 28px;
            right: 28px;
            bottom: 28px;
            border-top: 2px solid #9ea8b8;
            padding-top: 22px;
        }

        .sp-purpose-title {
            font-weight: 700;
            margin-bottom: 12px;
        }

        .sp-purpose-text {
            color: var(--sp-muted);
            line-height: 1.55;
            font-size: 13px;
        }

        /* =========================
           MAIN CONTENT
        ========================= */

        .sp-main {
            margin-left: 260px;
            padding: 104px 32px 40px;
            min-height: 100vh;
        }

        .sp-page-header {
            margin-bottom: 25px;
        }

        .sp-page-title {
            font-size: 29px;
            font-weight: 750;
            margin-bottom: 5px;
        }

        .sp-page-subtitle {
            color: var(--sp-muted);
            margin: 0;
        }

        /* =========================
           CARDS
        ========================= */

        .sp-card {
            background: white;
            border: 2px solid var(--sp-border);
            border-radius: 17px;
            padding: 25px;
            height: 100%;
        }

        .sp-card-title {
            font-size: 19px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .sp-card-subtitle {
            color: var(--sp-muted);
            font-size: 13px;
        }

        .sp-stat-label {
            color: var(--sp-muted);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .sp-stat-value {
            font-size: 24px;
            font-weight: 750;
            margin-bottom: 5px;
        }

        .sp-stat-description {
            color: var(--sp-muted);
            font-size: 12px;
        }

        /* =========================
           BUTTONS
        ========================= */

        .sp-btn {
            border: none;
            background: var(--sp-dark);
            color: white;
            border-radius: 12px;
            padding: 11px 20px;
            font-weight: 650;
            transition: .2s;
        }

        .sp-btn:hover {
            background: #273249;
            color: white;
            transform: translateY(-1px);
        }

        .sp-btn-outline {
            border: 2px solid #aab3c1;
            background: white;
            color: var(--sp-text);
            border-radius: 11px;
            padding: 9px 17px;
            font-weight: 600;
        }

        .sp-btn-outline:hover {
            background: #f1f3f6;
        }

        /* =========================
           FORM
        ========================= */

        .sp-label {
            font-weight: 650;
            margin-bottom: 7px;
            display: block;
        }

        .sp-input,
        .sp-select,
        .sp-textarea {
            width: 100%;
            border: 2px solid #d0d5dd;
            background: #f7f8fa;
            border-radius: 10px;
            padding: 10px 13px;
            color: var(--sp-text);
            outline: none;
        }

        .sp-input:focus,
        .sp-select:focus,
        .sp-textarea:focus {
            border-color: #8792a4;
            box-shadow: none;
        }

        .sp-textarea {
            min-height: 130px;
            resize: vertical;
        }

        .sp-help {
            font-size: 12px;
            color: var(--sp-muted);
            margin-top: 6px;
        }

        /* =========================
           RISK
        ========================= */

        .risk-score-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px 0 18px;
        }

        .risk-score-circle {
            width: 155px;
            height: 155px;
            border: 2px solid #737d8e;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
        }

        .risk-score-number {
            font-size: 35px;
            font-weight: 750;
        }

        .risk-score-label {
            color: var(--sp-muted);
        }

        .risk-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            padding: 6px 12px;
            border-radius: 20px;
            border: 2px solid #aab3c1;
            font-size: 12px;
            font-weight: 650;
            background: white;
        }

        .risk-high {
            color: var(--sp-high);
        }

        .risk-medium {
            color: var(--sp-medium);
        }

        .risk-low {
            color: var(--sp-low);
        }

        /* =========================
           PROGRESS
        ========================= */

        .factor-item {
            margin-bottom: 18px;
        }

        .factor-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
        }

        .factor-name {
            color: #596579;
        }

        .factor-value {
            color: var(--sp-muted);
            font-size: 12px;
        }

        .sp-progress {
            height: 10px;
            border-radius: 20px;
            background: #e8ebef;
            overflow: hidden;
        }

        .sp-progress-bar {
            height: 100%;
            border-radius: 20px;
            background: #aeb6c2;
        }

        /* =========================
           TABLE
        ========================= */

        .sp-table {
            width: 100%;
            border-collapse: collapse;
        }

        .sp-table th {
            font-size: 12px;
            font-weight: 700;
            color: #30394a;
            padding: 10px 8px;
            border-bottom: 2px solid #aeb6c2;
        }

        .sp-table td {
            padding: 13px 8px;
            border-bottom: 1px solid #b9c0ca;
            vertical-align: middle;
        }

        .sp-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* =========================
           MAP
        ========================= */

        .sp-map {
            min-height: 310px;
            border: 2px solid #d0d5dd;
            border-radius: 15px;
            background: #f2f4f7;
            position: relative;
            overflow: hidden;
        }

        .sp-map-shape {
            position: absolute;
            border: 2px solid #a5afbd;
            border-radius: 50%;
            opacity: .8;
        }

        .map-shape-1 {
            width: 340px;
            height: 100px;
            left: 12%;
            top: 23%;
            transform: rotate(10deg);
        }

        .map-shape-2 {
            width: 290px;
            height: 85px;
            right: 10%;
            top: 45%;
            transform: rotate(8deg);
        }

        .map-shape-3 {
            width: 210px;
            height: 70px;
            left: 25%;
            bottom: 15%;
            transform: rotate(18deg);
        }

        .map-marker {
            position: absolute;
            width: 38px;
            height: 38px;
            background: white;
            border: 2px solid #1b2433;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .map-marker-label {
            position: absolute;
            left: 42px;
            top: 8px;
            white-space: nowrap;
            font-size: 11px;
            color: #687386;
        }

        /* =========================
           CHART
        ========================= */

        .chart-container {
            position: relative;
            height: 220px;
        }

        /* =========================
           ALERT
        ========================= */

        .sp-alert {
            border: 2px solid #aab3c1;
            background: #f1f3f6;
            border-radius: 12px;
            padding: 13px 16px;
        }

        /* =========================
           REPORT
        ========================= */

        .report-description {
            background: #f3f4f6;
            border: 2px solid #d1d6df;
            border-radius: 12px;
            padding: 16px;
            color: #5d6879;
            line-height: 1.6;
        }

        .report-meta {
            color: var(--sp-muted);
            font-size: 12px;
        }

        /* =========================
           SHIPMENT TIMELINE
        ========================= */

        .shipment-timeline {
            position: relative;
            padding-left: 45px;
        }

        .shipment-timeline::before {
            content: "";
            position: absolute;
            left: 13px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: #aeb6c2;
        }

        .shipment-step {
            position: relative;
            margin-bottom: 28px;
        }

        .shipment-step:last-child {
            margin-bottom: 0;
        }

        .shipment-dot {
            position: absolute;
            left: -45px;
            top: 0;
            width: 28px;
            height: 28px;
            border: 2px solid #7c8798;
            border-radius: 50%;
            background: white;
            z-index: 2;
        }

        .shipment-dot.completed {
            background: var(--sp-dark);
            border-color: var(--sp-dark);
        }

        .shipment-step-title {
            font-weight: 700;
            margin-bottom: 3px;
        }

        .shipment-step-date {
            color: var(--sp-muted);
            font-size: 12px;
        }

        .ledger-field {
            margin-bottom: 12px;
        }

        .ledger-label {
            font-weight: 650;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .ledger-value {
            background: #f2f4f7;
            border: 2px solid #d0d5dd;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: 12px;
            word-break: break-word;
        }

        .qr-box {
            width: 100px;
            height: 100px;
            border: 3px solid #172033;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 18px;
            background:
                repeating-linear-gradient(
                    45deg,
                    #fff,
                    #fff 5px,
                    #172033 5px,
                    #172033 8px
                );
        }

        .qr-inner {
            background: white;
            padding: 10px;
        }

        /* =========================
           EMPTY STATE
        ========================= */

        .sp-empty {
            text-align: center;
            padding: 45px 20px;
            color: var(--sp-muted);
        }

        .sp-empty-icon {
            font-size: 34px;
            margin-bottom: 10px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 991px) {
            .sp-sidebar {
                width: 220px;
            }

            .sp-main {
                margin-left: 220px;
            }

            .sp-top-menu {
                gap: 2px;
            }

            .sp-top-link {
                padding: 9px 12px;
            }
        }

        @media (max-width: 767px) {
            .sp-navbar {
                height: auto;
                min-height: 70px;
                padding: 12px 15px;
                flex-wrap: wrap;
                gap: 10px;
            }

            .sp-top-menu {
                width: 100%;
                justify-content: center;
            }

            .sp-sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding: 15px;
                border-right: none;
                border-bottom: 1px solid #d1d6df;
                margin-top: 70px;
            }

            .sp-side-menu {
                flex-direction: row;
                overflow-x: auto;
            }

            .sp-side-link {
                white-space: nowrap;
            }

            .sp-purpose {
                display: none;
            }

            .sp-main {
                margin-left: 0;
                padding: 25px 15px 30px;
            }

            .sp-page-title {
                font-size: 24px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    @include('partials.navbar')
    @include('partials.sidebar')

    <main class="sp-main">

        @if(session('success'))
            <div class="sp-alert mb-4">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="sp-alert mb-4">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="sp-alert mb-4">
                <strong>Please check the following:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </main>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>
</html>