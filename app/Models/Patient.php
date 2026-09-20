<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'patient_no','first_name','last_name','dob','gender','email','phone','address',
        'national_id','dha_cr_id','dha_verified_at'
    ];

    protected $casts = [
        'dob' => 'date',
        'dha_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($patient) {
            if (empty($patient->patient_no)) {
                $patient->patient_no = 'PAT' . str_pad(Patient::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function ipdAdmissions()
    {
        return $this->hasMany(IpdAdmission::class);
    }

    public function opdVisits()
    {
        return $this->hasMany(OpdVisit::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function patientInsurances()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function insurance()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function hasInsurance(): bool
    {
        return $this->insurance()->where('is_active', true)->exists();
    }
}


