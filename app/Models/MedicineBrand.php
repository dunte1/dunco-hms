<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'manufacturer',
        'country',
        'description',
    ];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class, 'brand_id');
    }
}
