<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortalAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_portal_account_id', 'action', 'ip_address', 'user_agent',
    ];

    public function portalAccount(): BelongsTo
    {
        return $this->belongsTo(PatientPortalAccount::class);
    }
}
