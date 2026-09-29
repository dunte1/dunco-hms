<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeAssessment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'trainee_id', 'assessment_date', 'assessment_type', 'score',
        'grade', 'strengths', 'areas_for_improvement', 'comments', 'assessed_by',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'score' => 'decimal:2',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(TraineeRecord::class, 'trainee_id');
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
