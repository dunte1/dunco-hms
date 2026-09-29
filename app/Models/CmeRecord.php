<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'title',
        'provider',
        'credits_earned',
        'category',
        'date_from',
        'date_to',
        'certificate_path',
        'status',
    ];

    protected $casts = [
        'credits_earned' => 'decimal:1',
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
