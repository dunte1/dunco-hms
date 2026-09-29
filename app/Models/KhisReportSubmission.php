<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhisReportSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type', 'reporting_period_month', 'reporting_period_year',
        'facility_code', 'total_patients', 'total_visits',
        'report_data', 'status', 'submitted_by', 'submitted_at',
        'response_reference',
    ];

    protected $casts = [
        'report_data' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function submittedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
