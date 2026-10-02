<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

/**
 * Marks scheduled/confirmed appointments in the past as no-show.
 * Run daily (or hourly) via scheduler: php artisan appointments:mark-missed
 */
class MarkMissedAppointments extends Command
{
    protected $signature = 'appointments:mark-missed {--hours=2 : Mark appointments missed after N hours past scheduled time}';
    protected $description = 'Mark past scheduled/confirmed appointments as no-show';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $cutoff = now()->subHours($hours);

        $count = Appointment::whereIn('status', ['scheduled', 'confirmed'])
            ->where(function ($q) use ($cutoff) {
                $q->where('scheduled_at', '<', $cutoff);
            })
            ->update(['status' => 'no_show']);

        // Fallback for appointments using appointment_date column if present
        if (\Schema::hasColumn('appointments', 'appointment_date')) {
            $count += Appointment::whereIn('status', ['scheduled', 'confirmed'])
                ->where('appointment_date', '<', $cutoff->toDateString())
                ->where(function ($q) use ($cutoff) {
                    if (\Schema::hasColumn('appointments', 'scheduled_at')) {
                        $q->where('scheduled_at', '<', $cutoff);
                    }
                })
                ->update(['status' => 'no_show']);
        }

        $this->info("Marked {$count} appointment(s) as no-show.");
        return self::SUCCESS;
    }
}
