<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartographEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'labour_record_id', 'time_recorded', 'cervical_dilation', 'descent',
        'contractions_per_10', 'fetal_heart_rate', 'liquor', 'moulding',
        'maternal_pulse', 'maternal_bp', 'urine_output', 'oxytocin_dose',
        'notes', 'recorded_by',
    ];

    protected $casts = [
        'time_recorded' => 'datetime',
    ];

    public function labourRecord(): BelongsTo
    {
        return $this->belongsTo(LabourRecord::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
