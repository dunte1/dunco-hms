<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodScreeningResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id', 'hiv_test', 'hepatitis_b_test', 'hepatitis_c_test',
        'syphilis_test', 'malaria_test', 'blood_group_confirmation',
        'screened_by', 'screened_at', 'is_eligible', 'notes',
    ];

    protected $casts = [
        'screened_at' => 'datetime',
        'is_eligible' => 'boolean',
    ];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(BloodDonation::class);
    }

    public function screenedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'screened_by');
    }
}
