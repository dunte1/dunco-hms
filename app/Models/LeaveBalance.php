<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'leave_type_id', 'year',
        'entitled_days', 'used_days', 'carried_forward_days',
    ];

    protected $casts = [
        'entitled_days' => 'integer',
        'used_days' => 'integer',
        'carried_forward_days' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getRemainingDaysAttribute(): int
    {
        return $this->entitled_days + $this->carried_forward_days - $this->used_days;
    }

    public function getAvailableDaysAttribute(): int
    {
        return max(0, $this->remaining_days);
    }

    public function getUsagePercentageAttribute(): float
    {
        $total = $this->entitled_days + $this->carried_forward_days;
        if ($total <= 0) return 0;
        return round(($this->used_days / $total) * 100, 1);
    }

    /**
     * Auto-create balance entries for an employee based on active leave types
     */
    public static function ensureBalancesExist(Employee $employee, int $year): void
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        foreach ($leaveTypes as $lt) {
            static::firstOrCreate(
                ['employee_id' => $employee->id, 'leave_type_id' => $lt->id, 'year' => $year],
                ['entitled_days' => $lt->default_days]
            );
        }
    }
}
