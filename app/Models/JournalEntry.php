<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class JournalEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'entry_number',
        'date',
        'description',
        'reference_type',
        'reference_id',
        'fiscal_period_id',
        'status',
        'posted_by',
        'posted_at',
        'reversed_by',
        'reversed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'posted_at' => 'datetime',
        'reversed_at' => 'datetime',
    ];

    public function fiscalPeriod(): BelongsTo
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function reversedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function post(int $userId): void
    {
        if ($this->status !== 'draft') {
            throw new \LogicException('Only draft entries can be posted.');
        }

        $this->update([
            'status' => 'posted',
            'posted_by' => $userId,
            'posted_at' => now(),
        ]);
    }

    public function reverse(int $userId): self
    {
        if ($this->status !== 'posted') {
            throw new \LogicException('Only posted entries can be reversed.');
        }

        $this->update([
            'status' => 'reversed',
            'reversed_by' => $userId,
            'reversed_at' => now(),
        ]);

        $reversedEntry = static::create([
            'entry_number' => self::generateEntryNumber(),
            'date' => now()->toDateString(),
            'description' => "Reversal of {$this->entry_number}: {$this->description}",
            'reference_type' => static::class,
            'reference_id' => $this->id,
            'fiscal_period_id' => $this->fiscal_period_id,
            'status' => 'draft',
        ]);

        foreach ($this->lines as $line) {
            $reversedEntry->lines()->create([
                'account_id' => $line->account_id,
                'debit' => $line->credit,
                'credit' => $line->debit,
                'description' => "Reversal: {$line->description}",
            ]);
        }

        return $reversedEntry;
    }

    public function validateDebitEqualsCredit(): bool
    {
        $totalDebit = $this->lines->sum('debit');
        $totalCredit = $this->lines->sum('credit');

        return abs($totalDebit - $totalCredit) < 0.01;
    }

    public static function generateEntryNumber(): string
    {
        $prefix = 'JE-' . now()->format('Ymd');
        $lastEntry = static::where('entry_number', 'like', "{$prefix}-%")
            ->orderByDesc('entry_number')
            ->first();

        if ($lastEntry) {
            $sequence = (int) substr($lastEntry->entry_number, -4) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
