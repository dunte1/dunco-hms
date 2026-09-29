<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'examiner_id', 'ear_findings', 'nose_findings',
        'throat_findings', 'hearing_test', 'endoscopy_findings',
        'diagnosis', 'treatment',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }
}
