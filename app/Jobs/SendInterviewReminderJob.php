<?php

namespace App\Jobs;

use App\Models\Interview;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendInterviewReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $interviewId)
    {
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $interview = Interview::query()->find($this->interviewId);

        if (! $interview) {
            return;
        }

        Log::info('Interview reminder dispatched.', [
            'interview_id' => $interview->id,
            'scheduled_for' => $interview->scheduled_for?->toIso8601String(),
            'interviewer_email' => $interview->interviewer_email,
        ]);
    }
}
