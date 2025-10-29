<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name', 'patient_email', 'patient_phone', 'testimonial',
        'rating', 'treatment_received', 'doctor_name', 'patient_photo',
        'status', 'is_featured', 'admin_notes'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];
}
