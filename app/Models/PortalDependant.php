<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalDependant extends Model
{
    use HasFactory;

    protected $fillable = [
        'portal_account_id', 'patient_id', 'relationship', 'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function portalAccount(): BelongsTo
    {
        return $this->belongsTo(PatientPortalAccount::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
