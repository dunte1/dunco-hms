<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientIdentifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'identifier_type',
        'identifier_value',
        'issuing_authority',
        'expiry_date',
        'is_primary',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_primary' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
