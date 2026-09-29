<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSchedule extends Model
{
    protected $fillable = [
        'saved_report_id', 'frequency', 'day_of_week', 'day_of_month',
        'time_of_day', 'recipients', 'status', 'last_sent_at', 'next_run_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'last_sent_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function savedReport(): BelongsTo
    {
        return $this->belongsTo(SavedReport::class, 'saved_report_id');
    }
}
