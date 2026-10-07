<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\Marketing\PublishScheduledPost;
use App\Jobs\Marketing\GenerateDailyContent;
use App\Models\Marketing\ScheduledPost;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Jobs
Schedule::job(new \App\Jobs\SendAppointmentReminders)
    ->dailyAt('18:00')
    ->timezone('UTC')
    ->description('Send appointment reminders for tomorrow');

// Mark missed appointments (no-show) daily
Schedule::command('appointments:mark-missed')
    ->dailyAt('23:30')
    ->timezone('UTC')
    ->description('Mark past scheduled appointments as no-show');

Schedule::job(new \App\Jobs\SendPaymentReminders)
    ->dailyAt('10:00')
    ->timezone('UTC')
    ->description('Send payment reminders for overdue invoices');

// Marketing Module Scheduled Tasks
// Check for scheduled posts every minute
Schedule::call(function () {
    $scheduled = ScheduledPost::where('status', 'pending')
        ->where('scheduled_at', '<=', now())
        ->get();
    
    foreach ($scheduled as $post) {
        PublishScheduledPost::dispatch($post);
    }
})->name('publish-scheduled-posts')->everyMinute()->withoutOverlapping()->description('Publish scheduled marketing posts');

// Generate daily content at 8 AM
Schedule::job(new GenerateDailyContent('Daily Health Tip', 'facebook'))
    ->dailyAt('08:00')
    ->description('Generate daily Facebook health content');

Schedule::job(new GenerateDailyContent('Daily Health Tip', 'instagram'))
    ->dailyAt('09:00')
    ->description('Generate daily Instagram health content');

// Check for failed scheduled posts and retry (max 3 retries)
Schedule::call(function () {
    $failed = ScheduledPost::where('status', 'failed')
        ->where('retry_count', '<', 3)
        ->where('scheduled_at', '>=', now()->subHours(24))
        ->get();
    
    foreach ($failed as $post) {
        PublishScheduledPost::dispatch($post)->delay(now()->addMinutes(5));
    }
})->hourly()->description('Retry failed scheduled posts');

// Automated Database Backups - Daily via Spatie laravel-backup + BackupRecord tracking
$backupScheduleGuard = ! (
    config('cache.default') === 'database'
    && (empty(config('cache.stores.database.lock_table')) || empty(config('cache.stores.database.table')))
);

$backupSchedule = Schedule::call(function () {
    $result = app(\App\Services\BackupService::class)->create(
        manual: false,
        notes: 'Scheduled automated backup'
    );

    if (! ($result['success'] ?? false)) {
        \Illuminate\Support\Facades\Log::error('Scheduled backup failed', [
            'message' => $result['message'] ?? 'Unknown error',
        ]);
    }
})
    ->name('hms-backup-run')
    ->dailyAt(env('BACKUP_SCHEDULE_TIME', '02:00'))
    ->timezone(env('BACKUP_SCHEDULE_TIMEZONE', config('app.timezone', 'UTC')));

if ($backupScheduleGuard) {
    $backupSchedule->withoutOverlapping()->onOneServer();
}

$backupSchedule->description('Automated daily database and file backup (Spatie + BackupRecord)');

// Clean up old backups according to retention strategy
$backupClean = Schedule::command('backup:clean')
    ->weeklyOn((int) env('BACKUP_CLEANUP_DAY_OF_WEEK', 0), env('BACKUP_CLEANUP_TIME', '03:00'))
    ->timezone(env('BACKUP_SCHEDULE_TIMEZONE', config('app.timezone', 'UTC')));

if ($backupScheduleGuard) {
    $backupClean->withoutOverlapping();
}

$backupClean->description('Clean up old backups based on retention policy');

// Health-check backups (notifies if latest backup is too old / too large)
Schedule::command('backup:monitor')
    ->dailyAt(env('BACKUP_MONITOR_TIME', '04:00'))
    ->timezone(env('BACKUP_SCHEDULE_TIMEZONE', config('app.timezone', 'UTC')))
    ->description('Monitor backup health (age and storage size)');

// Queue health check (ensures workers are keeping up)
Schedule::command('hms:queue-health')
    ->hourly()
    ->description('Check queue worker health (pending/failed jobs)');

// Stock Alerts - Check daily at 7 AM for expiry and low stock
Schedule::job(new \App\Jobs\CheckStockAlerts)
    ->dailyAt('07:00')
    ->timezone('UTC')
    ->description('Check for stock expiry and low stock alerts');

// Pending Approvals - Check daily at 8 AM
Schedule::command('approvals:check')
    ->dailyAt('08:00')
    ->timezone('UTC')
    ->description('Check for pending approvals and send notifications');

// Pending Approvals - Check daily at 8 AM and notify approvers
Schedule::command('approvals:check')
    ->dailyAt('08:00')
    ->description('Check for pending approvals and send notifications');
