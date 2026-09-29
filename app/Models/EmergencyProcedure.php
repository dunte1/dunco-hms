<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyProcedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'emergency_admission_id', 'patient_id', 'procedure_name',
        'description', 'body_site', 'performed_by', 'performed_at',
        'outcome', 'complications',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    public function emergencyAdmission(): BelongsTo
    {
        return $this->belongsTo(EmergencyAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'performed_by');
    }
}
