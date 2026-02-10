<?php

namespace App\Console\Commands;

use App\Jobs\BuildPipelineMetricsJob;
use Illuminate\Console\Command;

class AtsSnapshotMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ats:snapshot-metrics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Snapshot ATS pipeline metrics into cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        BuildPipelineMetricsJob::dispatch();

        $this->info('Pipeline metrics snapshot dispatched.');

        return self::SUCCESS;
    }
}
