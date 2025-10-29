<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RfidTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'tag_id', 'tag_type', 'patient_id', 'employee_id', 'associated_name',
        'status', 'last_seen', 'last_location', 'notes'
    ];

    protected $casts = [
        'last_seen' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
