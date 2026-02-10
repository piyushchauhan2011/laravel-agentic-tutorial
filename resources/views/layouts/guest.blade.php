<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ATSLab') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
                    <div class="text-center mb-3">
                        <a href="/" class="d-inline-block text-decoration-none text-dark">
                            <x-application-logo style="width: 64px; height: 64px;" />
                            <div class="mt-2 fw-semibold">{{ config('app.name', 'ATSLab') }}</div>
                        </a>
                    </div>

                    <div class="card ats-shell border-0">
                        <div class="card-body p-4 p-md-5">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
