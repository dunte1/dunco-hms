<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NurseDepartment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function nurses(): HasMany
    {
        return $this->hasMany(Nurse::class);
    }
}
