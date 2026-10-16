<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid px-4">

        <a
            href="{{ route('dashboard') }}"
            class="navbar-brand fw-bold"
        >
            SiagaPangan
        </a>

        <div class="d-flex gap-4">

            <a
                href="{{ route('dashboard') }}"
                class="text-decoration-none text-dark fw-semibold"
            >
                Dashboard
            </a>

            <a
                href="{{ route('analysis') }}"
                class="text-decoration-none text-dark fw-semibold"
            >
                Analysis
            </a>

            <a
                href="{{ route('reports') }}"
                class="text-decoration-none text-dark fw-semibold"
            >
                Reports
            </a>

        </div>

    </div>
</nav>