<nav class="sp-navbar">

    <a href="{{ route('dashboard') }}" class="sp-brand">
        <span class="sp-brand-icon"></span>
        <span>SiagaPangan</span>
    </a>

    <div class="sp-top-menu">

        <a
            href="{{ route('dashboard') }}"
            class="sp-top-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            Dashboard
        </a>

        <a
            href="{{ route('analysis') }}"
            class="sp-top-link {{ request()->routeIs('analysis*') ? 'active' : '' }}"
        >
            Analysis
        </a>

        <a
            href="{{ route('reports') }}"
            class="sp-top-link {{ request()->routeIs('reports*') ? 'active' : '' }}"
        >
            Reports
        </a>

    </div>

</nav>