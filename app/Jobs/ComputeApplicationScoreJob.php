<?php

namespace App\Jobs;

use App\Models\Application;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ComputeApplicationScoreJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $applicationId)
    {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $application = Application::query()->with('candidate')->find($this->applicationId);

        if (! $application || ! $application->candidate) {
            return;
        }

        $score = match ($application->current_stage) {
            'applied' => 20,
            'screening' => 40,
            'interview' => 60,
            'offer' => 85,
            'hired' => 100,
            default => 10,
        };

        $application->candidate->update(['score' => $score]);
    }
}
