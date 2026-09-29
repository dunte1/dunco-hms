<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'rate', 'description', 'is_active', 'is_inclusive',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'is_inclusive' => 'boolean',
    ];

    public function calculateTax(float $amount): float
    {
        if ($this->is_inclusive) {
            return $amount - ($amount / (1 + $this->rate / 100));
        }
        return $amount * ($this->rate / 100);
    }
}
