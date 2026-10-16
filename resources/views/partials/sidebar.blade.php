<aside class="col-auto p-0">

    <div class="p-4" style="width: 230px;">

        <div class="d-flex flex-column gap-2">

            <a
                href="{{ route('dashboard') }}"
                class="btn text-start
                {{ request()->routeIs('dashboard') ? 'btn-secondary' : 'btn-light' }}"
            >
                <i class="bi bi-grid"></i>
                Dashboard
            </a>

            <a
                href="{{ route('analysis') }}"
                class="btn text-start
                {{ request()->routeIs('analysis') ? 'btn-secondary' : 'btn-light' }}"
            >
                <i class="bi bi-graph-up"></i>
                Analysis
            </a>

            <a
                href="{{ route('reports') }}"
                class="btn text-start
                {{ request()->routeIs('reports') ? 'btn-secondary' : 'btn-light' }}"
            >
                <i class="bi bi-file-text"></i>
                Reports
            </a>

        </div>

        <hr class="my-5">

        <strong>System Purpose</strong>

        <p class="small text-secondary mt-2">
            Detect food security risks, optimize distribution,
            and track assistance transparently.
        </p>

    </div>

</aside>