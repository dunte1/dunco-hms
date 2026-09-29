<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'type', 'subject', 'body', 'format', 'variables',
        'is_default', 'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type)->where('is_active', true);
    }

    public function render(array $data): string
    {
        $compiled = $this->body;
        foreach ($data as $key => $value) {
            $compiled = str_replace('{{ ' . $key . ' }}', (string) $value, $compiled);
        }
        return $compiled;
    }
}
