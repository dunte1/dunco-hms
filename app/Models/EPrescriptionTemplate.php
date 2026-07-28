<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EPrescriptionTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'template_structure',
        'default_fields',
        'header_text',
        'footer_text',
        'is_active',
        'created_by',
        'usage_count',
    ];

    protected $casts = [
        'template_structure' => 'array',
        'default_fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
