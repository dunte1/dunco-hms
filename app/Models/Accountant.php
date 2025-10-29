<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accountant extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountant_id', 'first_name', 'last_name', 'email', 'phone',
        'date_of_birth', 'gender', 'address', 'qualification', 'certification',
        'license_number', 'license_expiry', 'joining_date', 'salary', 'department',
        'is_active', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'license_expiry' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
