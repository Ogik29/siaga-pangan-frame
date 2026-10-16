<aside class="sp-sidebar" id="sp-sidebar">

    <div class="sp-sidebar-label">Workspace</div>
    <div class="sp-side-menu">

        <a
            href="{{ route('dashboard') }}"
            class="sp-side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <span class="sp-side-icon"><i class="bi bi-grid-1x2-fill"></i></span>
            <span>
                <strong>Dashboard</strong>
                <small>Situation overview</small>
            </span>
        </a>

        <a
            href="{{ route('analysis') }}"
            class="sp-side-link {{ request()->routeIs('analysis*') ? 'active' : '' }}"
        >
            <span class="sp-side-icon"><i class="bi bi-activity"></i></span>
            <span>
                <strong>Analysis</strong>
                <small>Risk & distribution</small>
            </span>
        </a>

        <a
            href="{{ route('reports') }}"
            class="sp-side-link {{ request()->routeIs('reports*') ? 'active' : '' }}"
        >
            <span class="sp-side-icon"><i class="bi bi-chat-square-text"></i></span>
            <span>
                <strong>Reports</strong>
                <small>Community signals</small>
            </span>
        </a>

    </div>

    <div class="sp-purpose">

        <div class="sp-purpose-kicker">
            <i class="bi bi-lightbulb"></i>
            Mission
        </div>

        <div class="sp-purpose-title">Protect food access early.</div>
        <div class="sp-purpose-text">
            Detect signals, coordinate action, and keep every movement
            visible from warehouse to region.
        </div>

    </div>

</aside>