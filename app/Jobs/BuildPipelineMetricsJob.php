<?php

namespace App\Jobs;

use App\Support\PipelineMetrics;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class BuildPipelineMetricsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('reports');
    }

    /**
     * Execute the job.
     */
    public function handle(PipelineMetrics $pipelineMetrics): void
    {
        $snapshot = $pipelineMetrics->funnel()
            ->values()
            ->all();

        Cache::put('ats:pipeline:snapshot', $snapshot, now()->addHours(12));
    }
}
