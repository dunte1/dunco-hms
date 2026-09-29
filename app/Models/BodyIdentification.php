<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyIdentification extends Model
{
    use HasFactory;

    protected $fillable = [
        'mortuary_record_id', 'identifier_name', 'identifier_relationship',
        'identification_method', 'identified_at', 'identified_by', 'notes',
    ];

    protected $casts = [
        'identified_at' => 'datetime',
    ];

    public function mortuaryRecord(): BelongsTo
    {
        return $this->belongsTo(MortuaryRecord::class);
    }

    public function identifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'identified_by');
    }
}
