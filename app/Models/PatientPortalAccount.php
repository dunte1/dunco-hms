<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientPortalAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'username', 'password_hash', 'email', 'phone',
        'is_active', 'last_login', 'preferences', 'two_factor_secret',
        'two_factor_enabled'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
        'preferences' => 'array',
        'two_factor_enabled' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
