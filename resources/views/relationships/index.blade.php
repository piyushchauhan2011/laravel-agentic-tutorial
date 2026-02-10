<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Database Relationships Explorer - {{ config('app.name', 'ATSLab') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 mb-0">Database Relationships Explorer</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard</a>
            <a href="/admin" class="btn btn-outline-secondary btn-sm">Admin Panel</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary btn-sm" type="submit">Log out</button>
            </form>
        </div>
    </div>

    {{-- Intro --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">Advanced Database Relationship Patterns</h2>
            <p class="text-secondary mb-0">This page demonstrates advanced SQL and Eloquent relationship patterns used in this Applicant Tracking System, including recursive/tree structures, polymorphic relations, ternary pivots, ARC (exclusive arc) relationships, and CTE (Common Table Expression) queries.</p>
        </div>
    </section>

    {{-- 1. Self-referencing / Recursive / Tree: Departments --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">🌳 Self-Referencing / Recursive / Tree: Departments</h2>
            <p class="small text-secondary mb-3">Each department can have a <code>parent_id</code> pointing to another department, forming a tree. Uses <code>belongsTo('parent')</code> and <code>hasMany('children')</code> on the same model.</p>

            @if($departments->isEmpty())
                <p class="text-secondary fst-italic mb-0">No departments yet. Create them in the <a href="/admin">Admin Panel</a>.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Level</th>
                                <th>Children</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departments as $dept)
                                <tr>
                                    <td class="fw-semibold">{{ str_repeat('— ', $dept->level) }}{{ $dept->name }}</td>
                                    <td>{{ $dept->parent?->name ?? '(root)' }}</td>
                                    <td>{{ $dept->level }}</td>
                                    <td>{{ $dept->children->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    {{-- 2. CTE: Department Hierarchy Tree --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">📊 CTE: Recursive Department Hierarchy</h2>
            <p class="small text-secondary mb-3">Uses a <code>WITH RECURSIVE</code> Common Table Expression to traverse the entire department tree and build full paths.</p>

            @if($departmentTree->isEmpty())
                <p class="text-secondary fst-italic mb-0">No department hierarchy data yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Full Path (via CTE)</th>
                                <th>Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentTree as $node)
                                <tr>
                                    <td class="fw-semibold">{{ $node->name }}</td>
                                    <td class="text-secondary">{{ $node->path }}</td>
                                    <td>{{ $node->level }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Depth Stats --}}
            @if($depthStats->isNotEmpty())
                <h3 class="h6 fw-semibold mt-4 mb-2">Depth Statistics (CTE)</h3>
                <div class="d-flex gap-3">
                    @foreach($depthStats as $stat)
                        <div class="bg-light rounded p-3 text-center">
                            <div class="h4 fw-bold mb-0" style="color: var(--ats-primary)">{{ $stat->department_count }}</div>
                            <div class="small text-secondary">Level {{ $stat->level }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- 3. Polymorphic: Tags --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">🏷️ Polymorphic Many-to-Many: Tags</h2>
            <p class="small text-secondary mb-3">Tags use <code>morphToMany</code> / <code>morphedByMany</code> to attach to Candidates, Positions, and Companies via a single <code>taggables</code> pivot table.</p>

            @if($tags->isEmpty())
                <p class="text-secondary fst-italic mb-0">No tags yet. Create them in the <a href="/admin">Admin Panel</a>.</p>
            @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <div class="border rounded-3 px-3 py-2" style="background: rgba(217, 119, 6, 0.08)">
                            <span class="fw-semibold">{{ $tag->name }}</span>
                            <span class="small text-secondary ms-1">({{ $tag->type }})</span>
                            <div class="small text-secondary mt-1">
                                {{ $tag->candidates_count }} candidates ·
                                {{ $tag->positions_count }} positions ·
                                {{ $tag->companies_count }} companies
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- 4. Ternary: Skill Assessments --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">🔺 Ternary Relationship: Skill Assessments</h2>
            <p class="small text-secondary mb-3">A <code>skill_assessments</code> table connects three entities: <strong>Candidate</strong>, <strong>Position</strong>, and <strong>Skill</strong> with a rating. This is a ternary (three-way) relationship.</p>

            @if($skills->isEmpty() && $assessments->isEmpty())
                <p class="text-secondary fst-italic mb-0">No skills or assessments yet. Create them in the <a href="/admin">Admin Panel</a>.</p>
            @else
                @if($skills->isNotEmpty())
                    <h3 class="h6 fw-semibold mb-2">Skills</h3>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($skills as $skill)
                            <span class="ats-chip">
                                {{ $skill->name }} <span class="text-secondary">({{ $skill->category }}, {{ $skill->assessments_count }} assessments)</span>
                            </span>
                        @endforeach
                    </div>
                @endif

                @if($assessments->isNotEmpty())
                    <h3 class="h6 fw-semibold mb-2">Recent Assessments</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Position</th>
                                    <th>Skill</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($assessments as $assessment)
                                    <tr>
                                        <td>{{ $assessment->candidate->full_name }}</td>
                                        <td>{{ $assessment->position->title }}</td>
                                        <td>{{ $assessment->skill->name }}</td>
                                        <td><span class="badge text-bg-info">{{ $assessment->rating }}/10</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </div>
    </section>

    {{-- 5. ARC: Feedback --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">⚡ ARC (Exclusive Arc): Feedback</h2>
            <p class="small text-secondary mb-3">Feedback belongs to <strong>either</strong> an Interview <strong>or</strong> an Offer, but not both. This is an Exclusive Arc pattern using nullable foreign keys with a constraint that exactly one must be set.</p>

            @if($feedbacks->isEmpty())
                <p class="text-secondary fst-italic mb-0">No feedback yet. Create them in the <a href="/admin">Admin Panel</a>.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Subject Type</th>
                                <th>Subject ID</th>
                                <th>Rating</th>
                                <th>Author</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($feedbacks as $fb)
                                <tr>
                                    <td>
                                        <span class="badge {{ $fb->subject_type === 'interview' ? 'text-bg-primary' : 'text-bg-warning' }}">
                                            {{ ucfirst($fb->subject_type ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>#{{ $fb->interview_id ?? $fb->offer_id ?? '-' }}</td>
                                    <td>{{ $fb->rating }}/10</td>
                                    <td>{{ $fb->author?->name ?? '-' }}</td>
                                    <td class="text-truncate" style="max-width: 250px">{{ $fb->comments ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    {{-- 6. Self-referencing through Candidates: Referrals --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">🔄 Self-Referencing: Candidate Referrals</h2>
            <p class="small text-secondary mb-3">A referral connects two candidates (<code>candidate_id</code> and <code>referred_by_candidate_id</code>) with a position. This demonstrates self-referencing through the candidates table.</p>

            @if($referrals->isEmpty())
                <p class="text-secondary fst-italic mb-0">No referrals yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Referred By</th>
                                <th>Position</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($referrals as $ref)
                                <tr>
                                    <td>{{ $ref->candidate->full_name }}</td>
                                    <td>{{ $ref->referrer->full_name }}</td>
                                    <td>{{ $ref->position->title }}</td>
                                    <td><span class="badge text-bg-secondary text-uppercase">{{ $ref->status }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    {{-- 7. Pipeline Funnel CTE --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-2">📈 CTE: Pipeline Funnel</h2>
            <p class="small text-secondary mb-3">Uses a CTE to aggregate application counts by stage.</p>

            @if($pipelineFunnel->isEmpty())
                <p class="text-secondary fst-italic mb-0">No application data yet.</p>
            @else
                <div class="d-flex flex-wrap gap-3">
                    @foreach($pipelineFunnel as $stage)
                        <div class="bg-light rounded-3 px-4 py-3 text-center">
                            <div class="h4 fw-bold mb-0" style="color: var(--ats-primary)">{{ $stage->total }}</div>
                            <div class="small text-secondary text-capitalize">{{ str_replace('_', ' ', $stage->stage) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Summary --}}
    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">📚 Relationship Pattern Summary</h2>
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="card border bg-light">
                        <div class="card-body py-2 px-3">
                            <strong>🌳 Self-Referencing / Tree</strong>
                            <p class="text-secondary mb-0">Department → parent_id → Department</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border bg-light">
                        <div class="card-body py-2 px-3">
                            <strong>🏷️ Polymorphic M2M</strong>
                            <p class="text-secondary mb-0">Tag → taggables → Candidate|Position|Company</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border bg-light">
                        <div class="card-body py-2 px-3">
                            <strong>🔺 Ternary</strong>
                            <p class="text-secondary mb-0">SkillAssessment → Candidate + Position + Skill</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border bg-light">
                        <div class="card-body py-2 px-3">
                            <strong>⚡ ARC (Exclusive Arc)</strong>
                            <p class="text-secondary mb-0">Feedback → Interview XOR Offer</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border bg-light">
                        <div class="card-body py-2 px-3">
                            <strong>🔄 Self-Referencing (Candidates)</strong>
                            <p class="text-secondary mb-0">Referral → candidate + referred_by_candidate</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="card border bg-light">
                        <div class="card-body py-2 px-3">
                            <strong>📊 CTE (Common Table Expressions)</strong>
                            <p class="text-secondary mb-0">Recursive hierarchy, depth stats, pipeline funnel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
