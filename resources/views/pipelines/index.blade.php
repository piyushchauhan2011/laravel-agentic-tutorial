<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pipeline Board - {{ config('app.name', 'ATSLab') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 mb-0">Pipeline Board</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard</a>
            <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary btn-sm">Applications</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary btn-sm" type="submit">Log out</button>
            </form>
        </div>
    </div>

    <section class="d-flex gap-3 overflow-auto pb-3">
        @foreach ($stages as $stage)
            @php $items = $groupedApplications[$stage] ?? collect(); @endphp
            <article class="ats-pipeline-column ats-shell p-3">
                <header class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="h6 mb-0 text-uppercase">{{ str_replace('_', ' ', $stage) }}</h2>
                    <span class="badge text-bg-secondary">{{ $items->count() }}</span>
                </header>

                <div class="d-grid gap-2">
                    @forelse ($items as $application)
                        <div class="card border-0 bg-light">
                            <div class="card-body py-2 px-3">
                                <p class="fw-semibold mb-1">{{ $application->candidate->full_name }}</p>
                                <p class="small mb-1 text-secondary">{{ $application->position->title }}</p>
                                <p class="small mb-0">
                                    <span class="badge text-bg-info text-uppercase">{{ $application->status }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="small text-secondary mb-0">No applications</p>
                    @endforelse
                </div>
            </article>
        @endforeach
    </section>
</main>
</body>
</html>
