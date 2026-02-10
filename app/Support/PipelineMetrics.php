<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PipelineMetrics
{
    public function funnel(): Collection
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

        return collect(DB::select($sql))->map(fn (object $row) => [
            'stage' => $row->current_stage,
            'total' => (int) $row->total,
        ]);
    }
}
