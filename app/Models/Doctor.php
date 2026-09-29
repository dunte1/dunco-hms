<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name','last_name','doctor_department_id','email','phone','qualification','years_experience',
        'consultation_fee','followup_fee','emergency_fee','home_visit_fee'
    ];

    protected $appends = ['full_name'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_department_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function qualifications(): HasMany
    {
        return $this->hasMany(PractitionerQualification::class);
    }

    public function practitionerLicences(): HasMany
    {
        return $this->hasMany(PractitionerLicence::class);
    }

    public function practitionerPrivileges(): HasMany
    {
        return $this->hasMany(PractitionerPrivilege::class);
    }

    public function oncallSchedules(): HasMany
    {
        return $this->hasMany(OncallSchedule::class);
    }

    public function cmeRecords(): HasMany
    {
        return $this->hasMany(CmeRecord::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}


