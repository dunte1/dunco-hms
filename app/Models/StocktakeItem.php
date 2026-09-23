<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StocktakeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stocktake_id', 'medicine_id', 'system_quantity',
        'physical_quantity', 'variance', 'remarks',
    ];

    protected $casts = [
        'system_quantity' => 'integer',
        'physical_quantity' => 'integer',
        'variance' => 'integer',
    ];

    public function stocktake(): BelongsTo
    {
        return $this->belongsTo(Stocktake::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    protected static function boot(): void
    {
        parent::boot();
        static::saving(function ($item) {
            if ($item->physical_quantity !== null && $item->system_quantity !== null) {
                $item->variance = $item->physical_quantity - $item->system_quantity;
            }
        });
    }
}
