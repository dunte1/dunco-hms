<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIssue extends Model
{
    protected $fillable = [
        'issue_number', 'store_id', 'issued_to_user_id',
        'issued_to_department', 'items', 'status',
        'issued_at', 'returned_at', 'notes',
    ];

    protected $casts = [
        'items' => 'array',
        'issued_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function issuedToUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_to_user_id');
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($issue) {
            if (!$issue->issue_number) {
                $issue->issue_number = 'SI-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
