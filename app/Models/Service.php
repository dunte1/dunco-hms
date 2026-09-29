<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'category', 'description', 'default_price',
        'currency', 'is_active', 'is_taxable', 'sort_order',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_taxable' => 'boolean',
    ];

    public function priceItems()
    {
        return $this->hasMany(PriceItem::class);
    }
}
