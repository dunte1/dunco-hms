<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WardRound extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipd_admission_id', 'patient_id', 'doctor_id', 'round_date',
        'findings', 'orders', 'status', 'created_by',
    ];

    protected $casts = [
        'round_date' => 'date',
    ];

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
