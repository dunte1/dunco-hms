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

// Automated Database Backups - Daily at 2 AM
Schedule::call(function () {
    $backupPath = storage_path('app/backups');
    if (!\Illuminate\Support\Facades\File::exists($backupPath)) {
        \Illuminate\Support\Facades\File::makeDirectory($backupPath, 0755, true);
    }
    
    $database = config('database.connections.' . config('database.default'));
    $driver = $database['driver'] ?? 'sqlite';
    
    if ($driver === 'sqlite') {
        $dbPath = $database['database'];
        if (!\Illuminate\Support\Facades\File::exists($dbPath)) {
            $dbPath = database_path($database['database']);
        }
        
        if (\Illuminate\Support\Facades\File::exists($dbPath)) {
            $filename = 'backup_auto_' . date('Y-m-d_His') . '.sql';
            \Illuminate\Support\Facades\File::copy($dbPath, $backupPath . '/' . $filename);
        }
    }
})->dailyAt('02:00')->description('Automated daily database backup');

// Clean up old backups - Keep only last 30 days, run weekly
Schedule::call(function () {
    $backupPath = storage_path('app/backups');
    if (\Illuminate\Support\Facades\File::exists($backupPath)) {
        $files = \Illuminate\Support\Facades\File::files($backupPath);
        $cutoffDate = now()->subDays(30);
        
        foreach ($files as $file) {
            if ($file->getMTime() < $cutoffDate->timestamp) {
                \Illuminate\Support\Facades\File::delete($file);
            }
        }
    }
})->weekly()->description('Clean up old backups (keep 30 days)');
