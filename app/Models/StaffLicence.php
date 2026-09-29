<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffLicence extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'licence_type', 'licence_number', 'issuing_body',
        'issue_date', 'expiry_date', 'status', 'document_path',
        'renewal_reminder_sent',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'renewal_reminder_sent' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
