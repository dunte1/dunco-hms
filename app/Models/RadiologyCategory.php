<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadiologyCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function tests(): HasMany
    {
        return $this->hasMany(RadiologyTest::class, 'category_id');
    }

    public function radiologyTests(): HasMany
    {
        return $this->hasMany(RadiologyTest::class, 'category_id');
    }
}
