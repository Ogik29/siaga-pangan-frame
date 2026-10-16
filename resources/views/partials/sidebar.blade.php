<aside class="sp-sidebar">

    <div class="sp-side-menu">

        <a
            href="{{ route('dashboard') }}"
            class="sp-side-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <span class="sp-number">1</span>
            <span>Dashboard</span>
        </a>

        <a
            href="{{ route('analysis') }}"
            class="sp-side-link {{ request()->routeIs('analysis*') ? 'active' : '' }}"
        >
            <span class="sp-number">2</span>
            <span>Analysis</span>
        </a>

        <a
            href="{{ route('reports') }}"
            class="sp-side-link {{ request()->routeIs('reports*') ? 'active' : '' }}"
        >
            <span class="sp-number">3</span>
            <span>Reports</span>
        </a>

    </div>

    <div class="sp-purpose">

        <div class="sp-purpose-title">
            System Purpose:
        </div>

        <div class="sp-purpose-text">
            Detect regional food risks, support preventive action,
            optimize stock distribution, and improve supply-chain
            transparency.
        </div>

    </div>

</aside>