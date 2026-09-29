<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerLicence extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'licence_type',
        'licence_number',
        'issuing_body',
        'issue_date',
        'expiry_date',
        'status',
        'document_path',
        'verified',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'verified' => 'boolean',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->status === 'active'
            && $this->expiry_date->diffInDays(now()) <= $days
            && $this->expiry_date->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expiry_date->isPast() && $this->status === 'active';
    }
}
