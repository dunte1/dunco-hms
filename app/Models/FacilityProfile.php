<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityProfile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'registration_number', 'kra_pin', 'phone', 'email',
        'address', 'city', 'county', 'country', 'logo_path',
        'operating_hours', 'services_offered', 'level', 'facility_type',
    ];

    protected $casts = [
        'operating_hours' => 'array',
        'services_offered' => 'array',
    ];

    public static function get(): static
    {
        return static::firstOrCreate(['slug' => 'default'], [
            'name' => config('app.name', 'Hospital'),
        ]);
    }
}
