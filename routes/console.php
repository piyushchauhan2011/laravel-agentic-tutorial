<?php

use App\Jobs\BuildPipelineMetricsJob;
use App\Jobs\SendDigestJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('horizon:snapshot')->everyFiveMinutes();
Schedule::job(new BuildPipelineMetricsJob)->hourly();
Schedule::command('ats:snapshot-metrics')->dailyAt('01:00');
Schedule::job(new SendDigestJob)->weeklyOn(1, '08:00');
