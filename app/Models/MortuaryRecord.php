<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MortuaryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'death_report_id', 'body_id', 'received_at', 'received_by',
        'storage_location', 'cause_of_death', 'status',
        'family_contact_name', 'family_contact_phone', 'identification_method',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];

    public function deathReport(): BelongsTo
    {
        return $this->belongsTo(DeathReport::class);
    }

    public function release(): HasOne
    {
        return $this->hasOne(MortuaryRelease::class);
    }
}
