<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorPass extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'visitor_log_id', 'patient_id', 'badge_number', 'visit_purpose',
        'ward_authorized', 'check_in_time', 'check_out_time', 'status', 'issued_by',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function visitorLog(): BelongsTo
    {
        return $this->belongsTo(VisitorLog::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
