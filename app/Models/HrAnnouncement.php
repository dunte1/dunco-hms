<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'target_audience', 'department_id', 'designation_id',
        'target_employee_ids', 'start_date', 'end_date', 'is_active',
        'attachment_path', 'created_by'
    ];

    protected $casts = [
        'target_employee_ids' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class);
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
