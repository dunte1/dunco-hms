<?php

namespace App\Models;

use App\Models\Scopes\BelongsToFacility;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MortuaryRecord extends Model
{
    use HasFactory;
    use BelongsToFacility;
    use Auditable;

    protected $fillable = [
        'death_report_id', 'body_id', 'received_at', 'received_by',
        'storage_location', 'cause_of_death', 'status',
        'family_contact_name', 'family_contact_phone', 'identification_method',
        'body_temperature', 'condition', 'slot_number',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'body_temperature' => 'decimal:1',
    ];

    public function deathReport(): BelongsTo
    {
        return $this->belongsTo(DeathReport::class);
    }

    public function release(): HasOne
    {
        return $this->hasOne(MortuaryRelease::class);
    }

    public function slotAssignments(): HasMany
    {
        return $this->hasMany(MortuarySlotAssignment::class);
    }

    public function identifications(): HasMany
    {
        return $this->hasMany(BodyIdentification::class);
    }

    public function postmortems(): HasMany
    {
        return $this->hasMany(Postmortem::class);
    }

    public function deathCertificates(): HasMany
    {
        return $this->hasMany(DeathCertificate::class);
    }
}


