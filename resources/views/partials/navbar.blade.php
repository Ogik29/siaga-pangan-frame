<nav class="sp-navbar">

    <div class="sp-navbar-start">
        <button
            type="button"
            class="sp-mobile-toggle"
            aria-label="Toggle navigation"
            aria-controls="sp-sidebar"
            aria-expanded="false"
        >
            <i class="bi bi-list"></i>
        </button>

        <a href="{{ route('dashboard') }}" class="sp-brand">
            <span class="sp-brand-icon">
                <i class="bi bi-shield-check"></i>
            </span>
            <span>
                <strong>Siaga</strong>Pangan
                <small>Food resilience platform</small>
            </span>
        </a>
    </div>

    <div class="sp-top-menu">

        <a
            href="{{ route('dashboard') }}"
            class="sp-top-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('analysis') }}"
            class="sp-top-link {{ request()->routeIs('analysis*') ? 'active' : '' }}"
        >
            <i class="bi bi-activity"></i>
            <span>Analysis</span>
        </a>

        <a
            href="{{ route('reports') }}"
            class="sp-top-link {{ request()->routeIs('reports*') ? 'active' : '' }}"
        >
            <i class="bi bi-chat-square-text"></i>
            <span>Reports</span>
        </a>

    </div>

    <div class="sp-navbar-status">
        <span class="sp-live-dot"></span>
        <span>System online</span>
    </div>

</nav>