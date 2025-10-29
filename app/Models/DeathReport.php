<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeathReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_number', 'patient_id', 'deceased_name', 'deceased_phone',
        'death_date', 'death_time', 'age_at_death', 'gender',
        'cause_of_death', 'place_of_death', 'attending_doctor_id',
        'attending_nurse_id', 'circumstances', 'notes', 'status'
    ];

    protected $casts = [
        'death_date' => 'date',
        'death_time' => 'datetime:H:i',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function attendingDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'attending_doctor_id');
    }

    public function attendingNurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class, 'attending_nurse_id');
    }
}
