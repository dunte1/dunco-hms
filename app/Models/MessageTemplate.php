<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'content', 'type', 'variables',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    public static function findByName(string $name): ?self
    {
        return static::where('name', $name)->first();
    }

    public function render(array $data = []): string
    {
        $replacements = [];
        foreach ($data as $key => $value) {
            $replacements['{' . $key . '}'] = (string) $value;
        }

        return strtr($this->content, $replacements);
    }

    public static function renderByName(string $name, array $data = [], ?string $fallback = null): ?string
    {
        $template = static::findByName($name);

        if (!$template) {
            return $fallback;
        }

        return $template->render($data);
    }
}
