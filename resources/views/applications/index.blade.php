<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applications - {{ config('app.name', 'ATSLab') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 mb-0">Applications</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-secondary btn-sm" type="submit">Log out</button>
            </form>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <section class="card ats-shell border-0 mb-4">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Create Application</h2>
            <form method="POST" action="{{ route('applications.store') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="position_id" class="form-label">Position</label>
                        <select id="position_id" name="position_id" class="form-select @error('position_id') is-invalid @enderror" required>
                            <option value="">Select position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" @selected(old('position_id') == $position->id)>
                                    {{ $position->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="candidate_id" class="form-label">Candidate</label>
                        <select id="candidate_id" name="candidate_id" class="form-select @error('candidate_id') is-invalid @enderror" required>
                            <option value="">Select candidate</option>
                            @foreach ($candidates as $candidate)
                                <option value="{{ $candidate->id }}" @selected(old('candidate_id') == $candidate->id)>
                                    {{ $candidate->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('candidate_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="current_stage" class="form-label">Current Stage</label>
                        <select id="current_stage" name="current_stage" class="form-select @error('current_stage') is-invalid @enderror" required>
                            @foreach (['applied', 'screening', 'interview', 'offer', 'hired', 'rejected'] as $stage)
                                <option value="{{ $stage }}" @selected(old('current_stage', 'applied') === $stage)>{{ ucfirst($stage) }}</option>
                            @endforeach
                        </select>
                        @error('current_stage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach (['active', 'withdrawn', 'hired', 'rejected'] as $status)
                                <option value="{{ $status }}" @selected(old('status', 'active') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="source" class="form-label">Source</label>
                        <input id="source" name="source" type="text" value="{{ old('source') }}"
                            class="form-control @error('source') is-invalid @enderror" placeholder="LinkedIn, Referral...">
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="applied_at" class="form-label">Applied Date</label>
                        <input id="applied_at" name="applied_at" type="date" value="{{ old('applied_at') }}"
                            class="form-control @error('applied_at') is-invalid @enderror">
                        @error('applied_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="cover_letter" class="form-label">Cover Letter</label>
                        <textarea id="cover_letter" name="cover_letter" rows="3"
                            class="form-control @error('cover_letter') is-invalid @enderror">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button id="create-application-submit" type="submit" class="btn btn-primary">Create Application</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card ats-shell border-0">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Application List</h2>
            @if ($applications->isEmpty())
                <p class="text-secondary mb-0">No applications yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Position</th>
                            <th>Stage</th>
                            <th>Status</th>
                            <th>Source</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($applications as $application)
                            <tr>
                                <td>{{ $application->candidate->full_name }}</td>
                                <td>{{ $application->position->title }}</td>
                                <td><span class="badge text-bg-info text-uppercase">{{ $application->current_stage }}</span></td>
                                <td><span class="badge text-bg-secondary text-uppercase">{{ $application->status }}</span></td>
                                <td>{{ $application->source ?: 'N/A' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
</main>
</body>
</html>
