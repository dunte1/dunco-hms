<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'event', 'channel', 'subject', 'body', 'variables', 'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeForEvent($query, string $event, ?string $channel = null)
    {
        $query->where('event', $event)->where('is_active', true);
        if ($channel) {
            $query->where('channel', $channel);
        }
        return $query;
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
