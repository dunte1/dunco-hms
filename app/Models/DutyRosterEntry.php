<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DutyRosterEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'roster_id', 'nurse_user_id', 'status', 'checked_in_at',
        'checked_out_at', 'substitute_user_id', 'notes',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function roster(): BelongsTo
    {
        return $this->belongsTo(DutyRoster::class, 'roster_id');
    }

    public function nurseUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_user_id');
    }

    public function substituteUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'substitute_user_id');
    }
}
