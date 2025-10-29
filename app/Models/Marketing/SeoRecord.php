<?php

namespace App\Models\Marketing;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'seoable_type', 'seoable_id', 'meta_title', 'meta_description',
        'meta_keywords', 'og_title', 'og_description', 'og_image',
        'schema_markup', 'canonical_url', 'focus_keyword', 'keyword_rank',
        'search_impressions', 'search_clicks', 'ctr', 'internal_links',
        'ai_keyword_suggestions', 'last_crawled_at'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'internal_links' => 'array',
        'ctr' => 'decimal:2',
        'last_crawled_at' => 'datetime',
        'keyword_rank' => 'integer',
    ];

    public function seoable()
    {
        return $this->morphTo();
    }
}
