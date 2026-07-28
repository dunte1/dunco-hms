<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_posting_id', 'first_name', 'last_name', 'email', 'phone',
        'address', 'resume_path', 'cover_letter', 'cover_letter_path', 'cover_letter_text',
        'status', 'interview_date', 'interview_notes', 'reviewed_by', 'reviewed_at',
        'employee_id', 'notes'
    ];

    protected $casts = [
        'interview_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
