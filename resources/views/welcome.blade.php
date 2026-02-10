<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATSLab</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="container py-5">
    <div class="ats-shell p-5">
        <span class="ats-chip">Laravel 12 + Boost + DDEV</span>
        <h1 class="display-5 mt-3 mb-3 ats-title">ATSLab Learning Project</h1>
        <p class="lead text-secondary mb-4">Small-scale applicant tracking system with Postgres, Redis queues, Horizon, Telescope, Filament, and API v1.</p>

        <div class="d-flex gap-2 flex-wrap">
            @auth
                <a class="btn btn-primary" href="{{ route('dashboard') }}">Open Dashboard</a>
            @else
                <a class="btn btn-primary" href="{{ route('login') }}">Sign In</a>
                <a class="btn btn-outline-primary" href="{{ route('register') }}">Create Account</a>
            @endauth
        </div>
    </div>
</div>
</body>
</html>
