<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
    public function handle(): void
    {
        $sql = <<<'SQL'
            WITH stage_counts AS (
                SELECT current_stage, COUNT(*) AS total
                FROM applications
                GROUP BY current_stage
            )
            SELECT current_stage, total
            FROM stage_counts
            ORDER BY total DESC, current_stage ASC
        SQL;

        $snapshot = collect(DB::select($sql))
            ->map(fn (object $row) => [
                'stage' => $row->current_stage,
                'total' => (int) $row->total,
            ])
            ->values()
            ->all();

        Cache::put('ats:pipeline:snapshot', $snapshot, now()->addHours(12));
    }
}
