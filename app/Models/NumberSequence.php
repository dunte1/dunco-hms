<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NumberSequence extends Model
{
    protected $fillable = [
        'name', 'prefix', 'next_number', 'padding', 'format', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'next_number' => 'integer',
        'padding' => 'integer',
    ];

    /**
     * Generate the next number in the sequence (concurrency-safe).
     */
    public static function next(string $name): string
    {
        return DB::transaction(function () use ($name) {
            $seq = static::where('name', $name)->lockForUpdate()->first();

            if (!$seq) {
                $seq = static::create([
                    'name' => $name,
                    'prefix' => strtoupper(substr($name, 0, 3)),
                    'next_number' => 1,
                    'padding' => 6,
                ]);
            }

            $number = $seq->next_number;
            $seq->increment('next_number');

            $formatted = $seq->prefix . str_pad((string) $number, $seq->padding, '0', STR_PAD_LEFT);

            return $formatted;
        });
    }

    /**
     * Peek at the next number without incrementing.
     */
    public static function peek(string $name): string
    {
        $seq = static::where('name', $name)->first();

        if (!$seq) {
            return '000001';
        }

        return $seq->prefix . str_pad((string) $seq->next_number, $seq->padding, '0', STR_PAD_LEFT);
    }
}
