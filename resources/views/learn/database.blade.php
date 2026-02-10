<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Database Learning - {{ config('app.name', 'ATSLab') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 mb-1">Database Relationships &amp; CTEs</h1>
            <p class="text-secondary mb-0">
                Use these patterns to model advanced recruiting workflows and build richer reporting queries.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard</a>
            <a href="/admin" class="btn btn-outline-secondary btn-sm">Filament Admin</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary btn-sm" type="submit">Log out</button>
            </form>
        </div>
    </div>

    <section class="ats-shell p-4 mb-4">
        <h2 class="h5">Relationship Patterns in the ATS Domain</h2>
        <p class="text-secondary mb-4">
            Each pattern below maps to tables already present in the ATS schema (or the next logical extension).
        </p>

        <div class="row g-3">
            @foreach ($relationshipTopics as $topic)
                <div class="col-12 col-lg-6">
                    <article class="card ats-card h-100">
                        <div class="card-body">
                            <h3 class="h6 mb-2">{{ $topic['title'] }}</h3>
                            <p class="text-secondary small mb-3">{{ $topic['summary'] }}</p>
                            <p class="mb-2">
                                <span class="text-uppercase text-secondary small">ATS Example</span><br>
                                {{ $topic['atsExample'] }}
                            </p>
                            <p class="mb-0">
                                <span class="text-uppercase text-secondary small">Modeling Hint</span><br>
                                <code>{{ $topic['implementation'] }}</code>
                            </p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>

    <section class="ats-shell p-4 mb-4">
        <h2 class="h5">CTE Query Patterns</h2>
        <p class="text-secondary mb-4">
            Use common table expressions (CTEs) for reusable steps, funnel analytics, and recursive hierarchies.
        </p>

        @foreach ($cteExamples as $example)
            <article class="mb-4">
                <h3 class="h6 mb-1">{{ $example['title'] }}</h3>
                <p class="text-secondary small mb-2">{{ $example['summary'] }}</p>
                <pre class="bg-light border rounded p-3 small mb-0"><code>{{ $example['sql'] }}</code></pre>
            </article>
        @endforeach
    </section>

    <section class="ats-shell p-4">
        <h2 class="h5">Next Practice Ideas</h2>
        <ul class="mb-0 text-secondary">
            <li>Sketch a self-referencing <code>positions</code> tree and run the recursive CTE on real data.</li>
            <li>Expand <code>application_stages</code> to track durations and summarize bottlenecks per role.</li>
            <li>Try building a polymorphic <code>notes</code> table that attaches to candidates and positions.</li>
        </ul>
    </section>
</main>
</body>
</html>
