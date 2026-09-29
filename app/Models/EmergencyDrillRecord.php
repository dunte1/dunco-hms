<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmergencyDrillRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'drill_type', 'drill_date', 'participants_count', 'assembly_point',
        'duration_minutes', 'performance_rating', 'lessons_learned', 'conducted_by',
    ];

    protected $casts = [
        'drill_date' => 'date',
    ];

    public function conductedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'conducted_by');
    }
}
