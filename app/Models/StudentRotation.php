<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotation_number', 'student_name', 'student_email', 'student_phone',
        'institution', 'program', 'department_id', 'supervisor_id',
        'start_date', 'end_date', 'status', 'objectives', 'evaluation',
        'performance_score', 'performance_rating', 'supervisor_comments',
        'student_feedback', 'evaluation_date', 'evaluated_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'evaluation_date' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }

    public function getDurationDaysAttribute(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($rot) {
            if (!$rot->rotation_number) {
                $rot->rotation_number = 'ROT-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
