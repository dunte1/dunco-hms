<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceAppraisal extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'appraised_by',
        'review_period',
        'appraisal_date',
        'period_start',
        'period_end',
        'overall_score',
        'overall_rating',
        'skill_ratings',
        'behavioral_ratings',
        'kpi_ratings',
        'strengths',
        'areas_for_improvement',
        'goals_for_next_period',
        'employee_comments',
        'appraiser_comments',
        'hr_comments',
        'status',
        'submitted_at',
        'reviewed_at',
        'approved_at',
        'promotion_recommended',
        'promotion_notes',
    ];

    protected $casts = [
        'appraisal_date' => 'date',
        'period_start' => 'date',
        'period_end' => 'date',
        'overall_score' => 'decimal:2',
        'skill_ratings' => 'array',
        'behavioral_ratings' => 'array',
        'kpi_ratings' => 'array',
        'submitted_at' => 'date',
        'reviewed_at' => 'date',
        'approved_at' => 'date',
        'promotion_recommended' => 'boolean',
    ];

    /**
     * Get the employee being appraised
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user who conducted the appraisal
     */
    public function appraiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'appraised_by');
    }
}
