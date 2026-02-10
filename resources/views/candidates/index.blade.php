<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Candidates - {{ config('app.name', 'ATSLab') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 mb-0">Candidates</h1>
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
            <h2 class="h5 mb-3">Create Candidate</h2>
            <form method="POST" action="{{ route('candidates.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name') }}"
                            class="form-control @error('full_name') is-invalid @enderror" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="current_company" class="form-label">Current Company</label>
                        <input id="current_company" name="current_company" type="text" value="{{ old('current_company') }}"
                            class="form-control @error('current_company') is-invalid @enderror">
                        @error('current_company')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="score" class="form-label">Score (0-100)</label>
                        <input id="score" name="score" type="number" min="0" max="100" value="{{ old('score', 0) }}"
                            class="form-control @error('score') is-invalid @enderror">
                        @error('score')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="resume_url" class="form-label">Resume URL</label>
                        <input id="resume_url" name="resume_url" type="url" value="{{ old('resume_url') }}"
                            class="form-control @error('resume_url') is-invalid @enderror">
                        @error('resume_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button id="create-candidate-submit" type="submit" class="btn btn-primary">Create Candidate</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card ats-shell border-0">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Candidate List</h2>
            @if ($candidates->isEmpty())
                <p class="text-secondary mb-0">No candidates yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current Company</th>
                            <th>Score</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($candidates as $candidate)
                            <tr>
                                <td>{{ $candidate->full_name }}</td>
                                <td>{{ $candidate->email }}</td>
                                <td>{{ $candidate->current_company ?: 'N/A' }}</td>
                                <td>{{ $candidate->score }}</td>
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
