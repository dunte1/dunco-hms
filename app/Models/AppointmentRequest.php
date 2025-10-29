<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name', 'email', 'phone', 'doctor_name', 'preferred_date', 'note', 'is_existing_patient'
    ];
}


