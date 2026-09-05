<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'manufacturer', 'dose_count', 'stock_quantity', 'expiry_date', 'batch_number', 'cost'];

    protected $casts = [
        'expiry_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function vaccinationRecords(): HasMany
    {
        return $this->hasMany(VaccinationRecord::class);
    }
}
