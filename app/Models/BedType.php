<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BedType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'charge_per_day', 'description'];

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }
}
