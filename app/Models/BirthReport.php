<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BirthReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number', 'baby_name', 'mother_name', 'father_name',
        'mother_phone', 'father_phone', 'birth_date', 'birth_time',
        'gender', 'birth_weight', 'birth_length', 'delivery_type',
        'attending_doctor_id', 'attending_nurse_id', 'complications',
        'notes', 'status'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'birth_time' => 'datetime:H:i',
        'birth_weight' => 'decimal:2',
        'birth_length' => 'decimal:2',
    ];

    public function attendingDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'attending_doctor_id');
    }

    public function attendingNurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'attending_nurse_id');
    }
}
