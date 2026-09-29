<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id', 'export_month', 'export_year', 'total_gross',
        'total_deductions', 'total_net', 'employee_count', 'status',
        'exported_by', 'exported_at', 'file_path',
    ];

    protected $casts = [
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_net' => 'decimal:2',
        'exported_at' => 'datetime',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function exportedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'exported_by');
    }
}
