<?php

use App\Models\ActivityLog;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Prune activity logs older than 60 days daily
Schedule::command('model:prune', ['--model' => [ActivityLog::class]])->daily();

// Process due recurring transactions daily at midnight
Schedule::command('app:generate-recurring-transactions')->dailyAt('00:05');

