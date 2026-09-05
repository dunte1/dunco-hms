<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MrdFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'file_number', 'file_type', 'physical_location',
        'status', 'digitized_path', 'digitized_at', 'last_accessed_at',
        'access_count', 'notes',
    ];

    protected $casts = [
        'digitized_at' => 'datetime',
        'last_accessed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(MrdFileMovement::class, 'mrd_file_id');
    }

    public static function generateFileNumber(): string
    {
        $prefix = 'MRD-' . now()->format('Y');
        $last = self::where('file_number', 'like', $prefix . '%')->latest('id')->value('file_number');
        if ($last) {
            $sequence = intval(substr($last, -5)) + 1;
        } else {
            $sequence = 1;
        }
        return $prefix . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }
}
