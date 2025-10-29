<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number', 'patient_id', 'case_handler_id', 'case_type',
        'description', 'priority', 'status', 'opened_date', 'resolved_date',
        'resolution_notes', 'notes'
    ];

    protected $casts = [
        'opened_date' => 'date',
        'resolved_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function caseHandler(): BelongsTo
    {
        return $this->belongsTo(CaseHandler::class);
    }
}
