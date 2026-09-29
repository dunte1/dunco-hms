<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnaesthesiaComplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'anaesthesia_record_id', 'patient_id', 'complication_type', 'severity',
        'description', 'treatment', 'outcome', 'occurred_at', 'reported_by',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public function anaesthesiaRecord(): BelongsTo
    {
        return $this->belongsTo(AnaesthesiaRecord::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
