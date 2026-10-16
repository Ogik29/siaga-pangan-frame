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

        /* =========================
           VISUAL SYSTEM OVERRIDES
        ========================= */

        :root {
            --sp-dark: #14213d;
            --sp-ink: #172033;
            --sp-muted: #6b7890;
            --sp-border: #e3e9f2;
            --sp-soft: #f5f8fc;
            --sp-accent: #1f8a70;
            --sp-accent-soft: #e6f5ef;
            --sp-shadow: 0 14px 35px rgba(20, 33, 61, .07);
        }

        body {
            background:
                radial-gradient(circle at 90% -10%, rgba(31, 138, 112, .08), transparent 30rem),
                #f6f8fb;
            color: var(--sp-ink);
            letter-spacing: -.01em;
        }

        .sp-navbar {
            height: 76px;
            padding: 0 30px;
            border-bottom: 1px solid rgba(218, 226, 237, .85);
            box-shadow: 0 4px 18px rgba(20, 33, 61, .04);
        }

        .sp-navbar-start,
        .sp-brand,
        .sp-navbar-status {
            display: flex;
            align-items: center;
        }

        .sp-navbar-start {
            gap: 14px;
        }

        .sp-brand {
            gap: 11px;
            font-size: 21px;
            letter-spacing: -.04em;
        }

        .sp-brand strong {
            color: var(--sp-accent);
        }

        .sp-brand small {
            display: block;
            margin-top: -2px;
            color: var(--sp-muted);
            font-size: 9px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .sp-brand-icon {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 13px;
            background: linear-gradient(135deg, #1f8a70, #146c5a);
            color: white;
            box-shadow: 0 8px 16px rgba(31, 138, 112, .22);
        }

        .sp-top-menu {
            gap: 4px;
            margin-left: auto;
            margin-right: 22px;
        }

        .sp-top-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 14px;
            border-radius: 10px;
            color: #647189;
            font-size: 13px;
        }

        .sp-top-link:hover {
            background: var(--sp-soft);
            color: var(--sp-ink);
        }

        .sp-top-link.active {
            background: var(--sp-accent-soft);
            color: #176a56;
        }

        .sp-navbar-status {
            gap: 7px;
            color: var(--sp-muted);
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        .sp-live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #32b47c;
            box-shadow: 0 0 0 4px rgba(50, 180, 124, .13);
        }

        .sp-mobile-toggle {
            display: none;
            border: 0;
            background: transparent;
            color: var(--sp-ink);
            font-size: 24px;
            line-height: 1;
        }

        .sp-sidebar {
            top: 76px;
            width: 248px;
            padding: 28px 18px;
            border-right: 1px solid rgba(218, 226, 237, .85);
            background: rgba(255, 255, 255, .92);
        }

        .sp-sidebar-label {
            padding: 0 13px 12px;
            color: #97a2b5;
            font-size: 10px;
            font-weight: 750;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .sp-side-menu {
            gap: 7px;
        }

        .sp-side-link {
            gap: 11px;
            padding: 11px 12px;
            border: 1px solid transparent;
            color: #5f6d84;
        }

        .sp-side-link:hover {
            background: #f6f9fc;
            color: var(--sp-ink);
        }

        .sp-side-link.active {
            border: 1px solid #cceade;
            background: linear-gradient(90deg, #eaf8f3, #f7fcfa);
            color: #176a56;
            box-shadow: 0 8px 18px rgba(31, 138, 112, .07);
        }

        .sp-side-link strong,
        .sp-side-link small {
            display: block;
        }

        .sp-side-link strong {
            font-size: 13px;
        }

        .sp-side-link small {
            margin-top: 2px;
            color: #9aa6b8;
            font-size: 10px;
        }

        .sp-side-icon {
            width: 31px;
            height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: #f0f4f8;
            color: #76849a;
            font-size: 14px;
        }

        .sp-side-link.active .sp-side-icon {
            background: #d6f1e6;
            color: #1f8a70;
        }

        .sp-purpose {
            left: 18px;
            right: 18px;
            bottom: 22px;
            border-top: 1px solid #e3e9f2;
            padding: 20px 12px 0;
        }

        .sp-purpose-kicker {
            color: var(--sp-accent);
            font-size: 10px;
            font-weight: 750;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .sp-purpose-kicker i {
            margin-right: 4px;
        }

        .sp-purpose-title {
            margin: 8px 0 6px;
            font-size: 14px;
        }

        .sp-purpose-text {
            font-size: 11px;
            line-height: 1.65;
        }

        .sp-main {
            margin-left: 248px;
            padding: 112px 38px 48px;
        }

        .sp-page-header {
            position: relative;
            margin-bottom: 30px;
        }

        .sp-page-header::after {
            content: "";
            display: block;
            width: 45px;
            height: 3px;
            margin-top: 14px;
            border-radius: 4px;
            background: var(--sp-accent);
        }

        .sp-page-title {
            color: var(--sp-ink);
            font-size: clamp(26px, 3vw, 34px);
            letter-spacing: -.045em;
        }

        .sp-page-subtitle {
            max-width: 720px;
            font-size: 14px;
        }

        .sp-card {
            border: 1px solid var(--sp-border);
            border-radius: 16px;
            padding: 23px;
            box-shadow: var(--sp-shadow);
        }

        .sp-card-title {
            margin-bottom: 16px;
            color: var(--sp-ink);
            font-size: 16px;
            letter-spacing: -.02em;
        }

        .sp-card-subtitle,
        .sp-stat-description {
            color: var(--sp-muted);
        }

        .sp-stat-label {
            color: #7d8aa0;
            font-size: 10px;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .sp-stat-value {
            margin: 7px 0 3px;
            color: var(--sp-ink);
            font-size: 28px;
            letter-spacing: -.04em;
        }

        .sp-btn {
            background: linear-gradient(135deg, #183152, #14213d);
            box-shadow: 0 8px 16px rgba(20, 33, 61, .14);
        }

        .sp-btn:hover {
            background: linear-gradient(135deg, #1f8a70, #146c5a);
        }

        .sp-btn-outline {
            border: 1px solid #d5deea;
            color: #52627a;
        }

        .sp-btn-outline:hover {
            border-color: #b9dfd0;
            background: var(--sp-accent-soft);
            color: #176a56;
        }

        .sp-input,
        .sp-select,
        .sp-textarea {
            border: 1px solid #dce4ee;
            background: #fbfcfe;
            border-radius: 10px;
        }

        .sp-input:focus,
        .sp-select:focus,
        .sp-textarea:focus {
            border-color: #58b99d;
            box-shadow: 0 0 0 4px rgba(31, 138, 112, .1);
        }

        .sp-table th {
            border-bottom: 1px solid #dfe6ef;
            color: #7b889d;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .sp-table td {
            border-bottom: 1px solid #edf1f5;
        }

        .sp-table tbody tr:hover {
            background: #fbfcfe;
        }

        .risk-badge {
            border: 0;
            background: #f1f4f8;
        }

        .risk-high {
            color: #c34d5c;
            background: #fff0f1;
        }

        .risk-medium {
            color: #b27a1e;
            background: #fff8e7;
        }

        .risk-low {
            color: #208563;
            background: #eaf8f2;
        }

        .sp-alert {
            border: 1px solid #cceade;
            background: #effaf5;
            color: #176a56;
            box-shadow: var(--sp-shadow);
        }

        @media (max-width: 991px) {
            .sp-navbar-status {
                display: none;
            }

            .sp-main {
                padding-right: 24px;
                padding-left: 24px;
            }
        }

        @media (max-width: 767px) {
            .sp-navbar {
                height: 70px;
                padding: 0 15px;
            }

            .sp-mobile-toggle {
                display: block;
            }

            .sp-brand {
                font-size: 19px;
            }

            .sp-brand small {
                display: none;
            }

            .sp-top-menu {
                order: 3;
                width: 100%;
                margin: 0;
                padding-top: 4px;
                justify-content: space-between;
            }

            .sp-top-link {
                flex: 1;
                justify-content: center;
                padding: 8px 5px;
                font-size: 12px;
            }

            .sp-top-link i {
                font-size: 14px;
            }

            .sp-sidebar {
                top: 70px;
                left: -280px;
                width: 265px;
                height: calc(100vh - 70px);
                transition: left .2s ease;
                box-shadow: 12px 0 30px rgba(20, 33, 61, .1);
            }

            .sp-sidebar.is-open {
                left: 0;
            }

            .sp-main {
                margin-left: 0;
                padding: 119px 15px 30px;
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

    <script>
        document.querySelector('.sp-mobile-toggle')?.addEventListener('click', function () {
            const sidebar = document.getElementById('sp-sidebar');
            const isOpen = sidebar.classList.toggle('is-open');

            this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    </script>

    @stack('scripts')

</body>
</html>