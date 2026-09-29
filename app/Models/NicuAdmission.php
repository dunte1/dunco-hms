<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NicuAdmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'newborn_id', 'patient_id', 'admission_date', 'reason',
        'admission_weight_grams', 'status', 'discharge_date',
        'discharge_weight_grams', 'discharge_destination', 'admitted_by',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'admission_weight_grams' => 'integer',
        'discharge_date' => 'datetime',
        'discharge_weight_grams' => 'integer',
    ];

    public function newborn(): BelongsTo
    {
        return $this->belongsTo(Newborn::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function admitUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function incubatorAssignments(): HasMany
    {
        return $this->hasMany(IncubatorAssignment::class);
    }
}
