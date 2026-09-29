<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'qualification_name',
        'institution',
        'country',
        'year_obtained',
        'certificate_number',
        'verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'year_obtained' => 'integer',
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
