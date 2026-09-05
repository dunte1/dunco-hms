<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortuaryRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'mortuary_record_id', 'released_to_name', 'released_to_relation',
        'released_to_id_number', 'released_to_phone', 'release_authorization_path',
        'released_at', 'released_by', 'receiving_facility', 'transport_method',
    ];

    protected $casts = [
        'released_at' => 'datetime',
    ];

    public function mortuaryRecord(): BelongsTo
    {
        return $this->belongsTo(MortuaryRecord::class);
    }
}
