<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OncallSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'department_id',
        'oncall_date',
        'shift_type',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'oncall_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }
}
