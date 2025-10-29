<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comment_replies', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // facebook, instagram, twitter, linkedin
            $table->string('platform_post_id');
            $table->string('platform_comment_id');
            $table->text('original_comment');
            $table->string('comment_author')->nullable();
            $table->string('sentiment')->nullable(); // positive, neutral, negative
            $table->text('ai_generated_reply');
            $table->text('approved_reply')->nullable();
            $table->string('status')->default('pending'); // pending, approved, posted, rejected
            $table->string('reply_platform_id')->nullable(); // ID of posted reply
            $table->boolean('requires_approval')->default(true);
            $table->datetime('replied_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['platform', 'platform_comment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment_replies');
    }
};
