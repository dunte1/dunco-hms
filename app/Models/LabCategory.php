<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function tests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'category_id');
    }

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class, 'category_id');
    }
}
