<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name','last_name','doctor_department_id','email','phone','qualification','years_experience'
    ];

    protected $appends = ['full_name'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(DoctorDepartment::class, 'doctor_department_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}


