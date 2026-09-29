<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TraineeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'trainee_type', 'institution', 'department_id',
        'program_name', 'start_date', 'end_date', 'supervisor_id', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(TraineeAssessment::class, 'trainee_id');
    }
}
