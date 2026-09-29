<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashierSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'opened_at', 'closed_at',
        'opening_balance', 'closing_balance', 'expected_balance',
        'variance', 'status', 'notes',
    ];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'expected_balance' => 'decimal:2',
        'variance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function open(int $userId, float $openingBalance, ?string $notes = null): self
    {
        return static::create([
            'user_id' => $userId,
            'opened_at' => now(),
            'opening_balance' => $openingBalance,
            'status' => 'open',
            'notes' => $notes,
        ]);
    }

    public function close(float $closingBalance, ?string $notes = null): bool
    {
        if ($this->status !== 'open') {
            return false;
        }

        // Calculate expected balance from payments in this session
        $expectedBalance = $this->calculateExpectedBalance();

        $this->update([
            'closed_at' => now(),
            'closing_balance' => $closingBalance,
            'expected_balance' => $expectedBalance,
            'variance' => $closingBalance - $expectedBalance,
            'status' => 'closed',
            'notes' => $notes ? ($this->notes ? $this->notes . "\n" . $notes : $notes) : $this->notes,
        ]);

        return true;
    }

    public function reconcile(float $closingBalance, ?string $notes = null): array
    {
        $this->close($closingBalance, $notes);

        return [
            'expected_balance' => $this->expected_balance,
            'closing_balance' => $this->closing_balance,
            'variance' => $this->variance,
            'is_balanced' => abs($this->variance) < 0.01,
        ];
    }

    protected function calculateExpectedBalance(): float
    {
        $paymentsTotal = Payment::whereHas('invoice', function ($q) {
            $q->where('status', '!=', 'cancelled');
        })
        ->where('created_at', '>=', $this->opened_at)
        ->where('created_at', '<=', $this->closed_at ?? now())
        ->where('status', '!=', 'pending')
        ->sum('amount');

        return $this->opening_balance + $paymentsTotal;
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
