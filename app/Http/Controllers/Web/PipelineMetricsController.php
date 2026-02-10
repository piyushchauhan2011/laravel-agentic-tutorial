<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Support\PipelineMetrics;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PipelineMetricsController extends Controller
{
    public function __invoke(PipelineMetrics $pipelineMetrics): JsonResponse
    {
        Gate::authorize('viewAny', Position::class);

        return response()->json([
            'data' => [
                'funnel' => $pipelineMetrics->funnel(),
            ],
        ]);
    }
}
