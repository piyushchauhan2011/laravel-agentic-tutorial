<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Positions - {{ config('app.name', 'ATSLab') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="ats-chip">ATS Learning Project</span>
            <h1 class="h3 mt-2 mb-0">Positions</h1>
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
            <h2 class="h5 mb-3">Create Position</h2>
            <form method="POST" action="{{ route('positions.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="company_id" class="form-label">Company</label>
                        <select id="company_id" name="company_id"
                            class="form-select @error('company_id') is-invalid @enderror" required>
                            <option value="">Select company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input id="title" name="title" type="text" value="{{ old('title') }}"
                            class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="department" class="form-label">Department</label>
                        <input id="department" name="department" type="text" value="{{ old('department') }}"
                            class="form-control @error('department') is-invalid @enderror">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="employment_type" class="form-label">Employment Type</label>
                        <select id="employment_type" name="employment_type"
                            class="form-select @error('employment_type') is-invalid @enderror" required>
                            @foreach (['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'internship' => 'Internship'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('employment_type', 'full_time') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status"
                            class="form-select @error('status') is-invalid @enderror" required>
                            @foreach (['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', 'draft') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="location" class="form-label">Location</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}"
                            class="form-control @error('location') is-invalid @enderror">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button id="create-position-submit" type="submit" class="btn btn-primary">Create Position</button>
                </div>
            </form>
        </div>
    </section>

    <section class="card ats-shell border-0">
        <div class="card-body p-4">
            <h2 class="h5 mb-3">Position List</h2>
            @if ($positions->isEmpty())
                <p class="text-secondary mb-0">No positions yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($positions as $position)
                            <tr>
                                <td>{{ $position->title }}</td>
                                <td>{{ $position->company->name }}</td>
                                <td>{{ $position->department ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge text-bg-secondary text-uppercase">
                                        {{ str_replace('_', ' ', $position->status) }}
                                    </span>
                                </td>
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
