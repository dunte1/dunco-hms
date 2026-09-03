<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShaMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'sha_member_number', 'national_id', 'first_name',
        'last_name', 'date_of_birth', 'gender', 'phone', 'tier_level',
        'employer_name', 'contribution_status', 'eligibility_status',
        'remaining_benefits', 'last_verified_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'remaining_benefits' => 'decimal:2',
        'last_verified_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function authorizations()
    {
        return $this->hasMany(ShaAuthorization::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function isEligible(): bool
    {
        return $this->eligibility_status === 'active' && $this->contribution_status === 'active';
    }

    public function getTierColorAttribute(): string
    {
        return match($this->tier_level) {
            'tier_1' => 'blue',
            'tier_2' => 'green',
            'tier_3' => 'purple',
            default => 'gray',
        };
    }
}
