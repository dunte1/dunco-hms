<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabSessionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'therapist_id', 'session_type', 'treatment_area',
        'session_notes', 'exercises_performed', 'progress_notes',
        'next_session_date',
    ];

    protected $casts = [
        'next_session_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
}
