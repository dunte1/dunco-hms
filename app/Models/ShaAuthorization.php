<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShaAuthorization extends Model
{
    use HasFactory;

    protected $fillable = [
        'authorization_number', 'patient_id', 'sha_member_id', 'service_type',
        'service_code', 'diagnosis_code', 'diagnosis_description',
        'authorized_amount', 'status', 'authorized_date', 'expiry_date',
        'notes', 'api_response',
    ];

    protected $casts = [
        'authorized_amount' => 'decimal:2',
        'authorized_date' => 'datetime',
        'expiry_date' => 'datetime',
        'api_response' => 'array',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function shaMember()
    {
        return $this->belongsTo(ShaMember::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => 'green',
            'pending' => 'yellow',
            'denied' => 'red',
            'cancelled' => 'gray',
            'expired' => 'orange',
            default => 'gray',
        };
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
