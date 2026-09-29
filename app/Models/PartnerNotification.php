<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'partner_name',
        'partner_phone',
        'partner_traced',
        'notification_method',
        'status',
        'notified_at',
        'tested_at',
        'hts_encounter_id',
    ];

    protected $casts = [
        'partner_traced' => 'boolean',
        'notified_at' => 'datetime',
        'tested_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function htsEncounter(): BelongsTo
    {
        return $this->belongsTo(HtsEncounter::class);
    }
}
