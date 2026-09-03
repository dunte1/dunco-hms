<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShaProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'facility_code', 'county', 'sub_county',
        'tier_level', 'accreditation_number', 'contact_person', 'phone',
        'email', 'address', 'api_base_url', 'api_key', 'api_secret',
        'certificate_path', 'is_active', 'last_verified_at', 'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_verified_at' => 'datetime',
    ];

    public function members()
    {
        return $this->hasMany(ShaMember::class);
    }

    public function authorizations()
    {
        return $this->hasManyThrough(ShaAuthorization::class, ShaMember::class);
    }

    public function isConfigured(): bool
    {
        return filled($this->api_base_url) && filled($this->api_key);
    }

    public function getVerificationStatusAttribute(): string
    {
        if (!$this->last_verified_at) return 'unverified';
        if ($this->last_verified_at->diffInDays(now()) > 30) return 'expired';
        return 'verified';
    }
}
