<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class LogActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $logData
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            ActivityLog::create($this->logData);
        } catch (\Throwable $e) {
            // ponytail: Basic error logging ceiling. If centralized alerting (Sentry/Bugsnag) is needed later, replace with report($e).
            Log::error('LogActivityJob: Failed to create activity log', [
                'error' => $e->getMessage(),
                'data' => $this->logData,
            ]);
        }
    }
}
