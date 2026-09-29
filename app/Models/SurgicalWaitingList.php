<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurgicalWaitingList extends Model
{
    use HasFactory;

    protected $table = 'surgical_waiting_list';

    protected $fillable = [
        'patient_id', 'procedure_name', 'urgency', 'priority',
        'added_by', 'added_date', 'target_date', 'status', 'notes',
    ];

    protected $casts = [
        'added_date' => 'date',
        'target_date' => 'date',
        'priority' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'added_by');
    }
}
