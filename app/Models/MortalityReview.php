<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortalityReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'death_report_id', 'patient_id', 'review_date', 'review_type',
        'diagnosis', 'contributing_factors', 'preventability', 'recommendations',
        'status', 'reviewed_by',
    ];

    protected $casts = [
        'review_date' => 'date',
    ];

    public function deathReport(): BelongsTo
    {
        return $this->belongsTo(DeathReport::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
