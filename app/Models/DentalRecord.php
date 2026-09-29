<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'dentist_id', 'tooth_number', 'procedure_type',
        'diagnosis', 'treatment_notes', 'cost', 'status',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }
}
