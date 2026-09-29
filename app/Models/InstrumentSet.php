<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentSet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description', 'instrument_count', 'is_active'];

    protected $casts = [
        'instrument_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function cycleRecords()
    {
        return $this->hasMany(CssdCycleRecord::class);
    }

    public function issueRecords()
    {
        return $this->hasMany(CssdIssueRecord::class);
    }
}
