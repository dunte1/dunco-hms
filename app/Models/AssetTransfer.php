<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id', 'from_location', 'to_location', 'from_department_id',
        'to_department_id', 'transfer_date', 'reason', 'transferred_by',
    ];

    protected $casts = [
        'transfer_date' => 'date',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'from_department_id');
    }

    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'to_department_id');
    }

    public function transferredByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
