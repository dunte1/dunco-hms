<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_name', 'visitor_phone', 'visitor_email', 'visitor_id_number',
        'visitor_type', 'patient_id', 'patient_name', 'purpose', 'department',
        'contact_person', 'check_in_time', 'check_out_time', 'status',
        'notes', 'badge_number'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
