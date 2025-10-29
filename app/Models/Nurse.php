<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nurse extends Model
{
    use HasFactory;

    protected $fillable = [
        'nurse_id', 'first_name', 'last_name', 'email', 'phone', 'date_of_birth',
        'gender', 'nurse_department_id', 'qualification', 'license_number',
        'license_expiry', 'address', 'joining_date', 'salary', 'shift',
        'is_active', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'license_expiry' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(NurseDepartment::class, 'nurse_department_id');
    }

    public function birthReports(): HasMany
    {
        return $this->hasMany(BirthReport::class);
    }

    public function deathReports(): HasMany
    {
        return $this->hasMany(DeathReport::class);
    }

    public function operationReports(): HasMany
    {
        return $this->hasMany(OperationReport::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
