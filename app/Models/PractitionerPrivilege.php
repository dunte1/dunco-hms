<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PractitionerPrivilege extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'privilege_id',
        'granted_date',
        'expiry_date',
        'status',
        'granted_by',
        'revoked_by',
        'revoked_at',
        'notes',
    ];

    protected $casts = [
        'granted_date' => 'date',
        'expiry_date' => 'date',
        'revoked_at' => 'datetime',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function privilege(): BelongsTo
    {
        return $this->belongsTo(Privilege::class);
    }

    public function grantor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function revoker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }
}
