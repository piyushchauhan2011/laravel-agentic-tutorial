<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\View\View;

class PipelineController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Application::class);

        $stages = collect(['applied', 'screening', 'interview', 'offer', 'hired', 'rejected']);

        $applications = Application::query()
            ->with([
                'candidate:id,full_name,email',
                'position:id,title',
            ])
            ->orderByDesc('created_at')
            ->get();

        $grouped = $stages
            ->mapWithKeys(fn (string $stage) => [$stage => $applications->where('current_stage', $stage)->values()])
            ->all();

        return view('pipelines.index', [
            'stages' => $stages,
            'groupedApplications' => $grouped,
        ]);
    }
}
