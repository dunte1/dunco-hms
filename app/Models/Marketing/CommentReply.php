<?php

namespace App\Models\Marketing;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform', 'platform_post_id', 'platform_comment_id',
        'original_comment', 'comment_author', 'sentiment',
        'ai_generated_reply', 'approved_reply', 'status',
        'reply_platform_id', 'requires_approval', 'replied_at',
        'reviewed_by', 'metadata'
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'replied_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
