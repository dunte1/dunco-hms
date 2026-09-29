<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FpVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'visit_date', 'method', 'previous_method', 'side_effects',
        'satisfaction_score', 'counseled_by', 'next_visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function counseledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counseled_by');
    }
}
