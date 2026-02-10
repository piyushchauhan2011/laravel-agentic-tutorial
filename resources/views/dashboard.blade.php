<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ATSLab Dashboard</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 ats-title mb-0">Recruiting Operations Dashboard</h1>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-secondary btn-sm" type="submit">Log out</button>
        </form>
    </div>

    <section class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card ats-card ats-shell h-100">
                <div class="card-body">
                    <h2 class="h6 text-secondary">Positions</h2>
                    <p class="display-6 mb-0">{{ $positionsCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card ats-card ats-shell h-100">
                <div class="card-body">
                    <h2 class="h6 text-secondary">Candidates</h2>
                    <p class="display-6 mb-0">{{ $candidatesCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card ats-card ats-shell h-100">
                <div class="card-body">
                    <h2 class="h6 text-secondary">Applications</h2>
                    <p class="display-6 mb-0">{{ $applicationsCount }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="ats-shell p-4 mb-4">
        <h2 class="h5">Operations & Observability</h2>
        <div class="d-flex flex-wrap gap-2 mt-3">
            <a class="btn btn-sm btn-primary" href="{{ route('positions.index') }}">Positions</a>
            <a class="btn btn-sm btn-primary" href="/admin">Filament Admin</a>
            <a class="btn btn-sm btn-outline-primary" href="/horizon">Horizon</a>
            <a class="btn btn-sm btn-outline-primary" href="/telescope">Telescope</a>
        </div>
    </section>

    <section class="ats-shell p-4">
        <h2 class="h5">API</h2>
        <p class="mb-1">Versioned ATS API is available under <code>/api/v1</code>.</p>
        <p class="mb-0">Example: <code>GET /api/v1/positions</code> and <code>GET /api/v1/metrics/pipeline</code></p>
    </section>
</main>
</body>
</html>
