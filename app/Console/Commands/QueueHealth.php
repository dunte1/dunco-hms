<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;

class QueueHealth extends Command
{
    protected $signature = 'hms:queue-health {--fail-threshold=50 : Mark unhealthy when pending jobs exceed this}';

    protected $description = 'Check queue worker health: pending jobs, failed jobs, and connection';

    public function handle(): int
    {
        $connection = config('queue.default', 'database');
        $this->info("Queue connection: {$connection}");

        try {
            $pending = (int) Queue::size();
            $this->line("Pending jobs: {$pending}");
        } catch (\Throwable $e) {
            $this->error('Unable to read queue size: ' . $e->getMessage());

            return self::FAILURE;
        }

        $failed = 0;
        try {
            if (Schema::hasTable('failed_jobs')) {
                $failed = (int) DB::table('failed_jobs')->count();
            }
            $this->line("Failed jobs: {$failed}");
        } catch (\Throwable $e) {
            $this->warn('Failed jobs table unavailable: ' . $e->getMessage());
        }

        $threshold = (int) $this->option('fail-threshold');

        if ($failed === 0 && $pending <= $threshold) {
            $this->info('Queue health: OK');

            return self::SUCCESS;
        }

        if ($failed > 0) {
            $this->warn("Queue health: DEGRADED — {$failed} failed job(s). Run: php artisan queue:failed");
        }

        if ($pending > $threshold) {
            $this->warn("Queue health: BACKLOG — {$pending} pending jobs (threshold {$threshold}). Ensure queue:work is running.");
        }

        return self::FAILURE;
    }
}
