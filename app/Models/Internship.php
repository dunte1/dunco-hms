<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_number', 'employee_id', 'intern_name', 'intern_email',
        'intern_phone', 'institution', 'program', 'department_id',
        'supervisor_id', 'start_date', 'end_date', 'status',
        'description', 'supervisor_notes', 'evaluation',
        'performance_score', 'performance_rating',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function getDurationDaysAttribute(): ?int
    {
        $end = $this->end_date ?? now();
        return $this->start_date->diffInDays($end) + 1;
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($intern) {
            if (!$intern->internship_number) {
                $intern->internship_number = 'INT-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
