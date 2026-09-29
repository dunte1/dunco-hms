<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounsellingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'session_date', 'session_type', 'presenting_issue',
        'interventions_used', 'patient_response', 'risk_level',
        'next_session_date', 'counsellor_id',
    ];

    protected $casts = [
        'session_date' => 'date',
        'next_session_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function counsellor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counsellor_id');
    }
}
