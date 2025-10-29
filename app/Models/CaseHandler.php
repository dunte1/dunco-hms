<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseHandler extends Model
{
    use HasFactory;

    protected $fillable = [
        'handler_id', 'first_name', 'last_name', 'email', 'phone',
        'specialization', 'qualifications', 'address', 'joining_date',
        'salary', 'is_active', 'notes'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function cases(): HasMany
    {
        return $this->hasMany(PatientCase::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
