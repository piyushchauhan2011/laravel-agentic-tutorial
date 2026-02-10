<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PipelineMetricsController extends Controller
{
    public function __invoke(): JsonResponse
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

        $funnel = collect(DB::select($sql))->map(fn (object $row) => [
            'stage' => $row->current_stage,
            'total' => (int) $row->total,
        ]);

        return response()->json([
            'data' => [
                'funnel' => $funnel,
            ],
        ]);
    }
}
