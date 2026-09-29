<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TbContact extends Model
{
    protected $fillable = [
        'tb_case_id', 'contact_name', 'contact_phone', 'contact_relationship',
        'screened', 'screening_result', 'hts_encounter_id', 'screened_date',
    ];

    protected $casts = [
        'screened' => 'boolean',
        'screened_date' => 'date',
    ];

    public function tbCase(): BelongsTo
    {
        return $this->belongsTo(TbCase::class);
    }
}
