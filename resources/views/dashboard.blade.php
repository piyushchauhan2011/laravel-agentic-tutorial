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
            <a class="btn btn-sm btn-primary" href="{{ route('candidates.index') }}">Candidates</a>
            <a class="btn btn-sm btn-primary" href="{{ route('applications.index') }}">Applications</a>
            <a class="btn btn-sm btn-primary" href="{{ route('pipelines.index') }}">Pipeline Board</a>
            <a class="btn btn-sm btn-primary" href="{{ route('learn.database') }}">Database Learning</a>
            <a class="btn btn-sm btn-primary" href="/admin">Filament Admin</a>
            <a class="btn btn-sm btn-outline-primary" href="/horizon">Horizon</a>
            <a class="btn btn-sm btn-outline-primary" href="/telescope">Telescope</a>
        </div>
    </section>

    <section
        class="ats-shell p-4 mb-4"
        x-data="{
            loading: true,
            error: '',
            funnel: [],
            async load() {
                this.loading = true;
                this.error = '';

                try {
                    const response = await fetch('{{ route('web.metrics.pipeline') }}', {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }

                    const payload = await response.json();
                    this.funnel = payload?.data?.funnel ?? [];
                } catch (error) {
                    this.error = 'Unable to load pipeline metrics.';
                    this.funnel = [];
                } finally {
                    this.loading = false;
                }
            },
            maxTotal() {
                if (!this.funnel.length) {
                    return 1;
                }

                return Math.max(...this.funnel.map((row) => Number(row.total) || 0), 1);
            },
            stageLabel(stage) {
                return String(stage || '')
                    .replaceAll('_', ' ')
                    .replace(/\b\w/g, (char) => char.toUpperCase());
            },
        }"
        x-init="load()"
    >
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h5 mb-0">Pipeline Funnel Metrics</h2>
            <button class="btn btn-sm btn-outline-secondary" type="button" @click="load()">Refresh</button>
        </div>

        <div x-show="loading" class="text-secondary">Loading metrics...</div>

        <div x-show="!loading && error" class="alert alert-warning mb-0" role="alert" x-text="error"></div>

        <div x-show="!loading && !error && funnel.length === 0" class="text-secondary">
            No application stage metrics yet.
        </div>

        <div x-show="!loading && !error && funnel.length > 0" class="d-grid gap-3">
            <template x-for="row in funnel" :key="row.stage">
                <div>
                    <div class="d-flex justify-content-between align-items-center small mb-1">
                        <span class="fw-semibold" x-text="stageLabel(row.stage)"></span>
                        <span class="text-secondary" x-text="row.total"></span>
                    </div>
                    <div class="progress" style="height: 0.75rem;">
                        <div
                            class="progress-bar"
                            role="progressbar"
                            :style="`width: ${Math.max((Number(row.total) || 0) / maxTotal() * 100, 3)}%`"
                            :aria-valuenow="row.total"
                            aria-valuemin="0"
                            :aria-valuemax="maxTotal()"
                            x-text="row.total"
                        ></div>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section class="ats-shell p-4">
        <h2 class="h5">Alpine.js Playground</h2>
        <div
            x-data="{
                showTips: false,
                nextCandidate: 1,
                quickStages: ['applied', 'screening'],
                addStage() {
                    const sequence = ['applied', 'screening', 'interview', 'offer', 'hired'];
                    const next = sequence[this.quickStages.length] ?? 'hired';
                    this.quickStages.push(next);
                },
                reset() {
                    this.showTips = false;
                    this.nextCandidate = 1;
                    this.quickStages = ['applied', 'screening'];
                },
            }"
        >
            <p class="text-secondary mb-3">Use this as a quick reference for Alpine state, list rendering, and event handling.</p>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <button class="btn btn-sm btn-outline-primary" type="button" @click="nextCandidate++">
                    Next Candidate #<span x-text="nextCandidate"></span>
                </button>
                <button class="btn btn-sm btn-outline-secondary" type="button" @click="showTips = !showTips">
                    <span x-text="showTips ? 'Hide Tips' : 'Show Tips'"></span>
                </button>
                <button class="btn btn-sm btn-outline-success" type="button" @click="addStage()">Add Stage</button>
                <button class="btn btn-sm btn-outline-danger" type="button" @click="reset()">Reset</button>
            </div>

            <div class="mb-3" x-show="showTips">
                <div class="alert alert-info mb-0">
                    Tip: this block is toggled with <code>x-show</code> and button events.
                </div>
            </div>

            <div>
                <h3 class="h6">Sample Stage Progression</h3>
                <div class="d-flex flex-wrap gap-2">
                    <template x-for="(stage, index) in quickStages" :key="`${stage}-${index}`">
                        <span class="badge text-bg-primary text-uppercase" x-text="stage"></span>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <section class="ats-shell p-4 mt-4">
        <h2 class="h5">API</h2>
        <p class="mb-1">Versioned ATS API is available under <code>/api/v1</code>.</p>
        <p class="mb-0">Example: <code>GET /api/v1/positions</code> and <code>GET /api/v1/metrics/pipeline</code></p>
    </section>
</main>
</body>
</html>
