<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('type')->default('social'); // social, blog, email, sms
            $table->string('platform')->nullable(); // facebook, instagram, twitter, linkedin, tiktok
            $table->text('hashtags')->nullable();
            $table->text('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('status')->default('draft'); // draft, approved, published, archived
            $table->boolean('is_ai_generated')->default(false);
            $table->string('ai_model')->nullable();
            $table->text('ai_prompt')->nullable();
            $table->json('media_urls')->nullable(); // Array of image/video URLs
            $table->foreignId('campaign_id')->nullable()->constrained('marketing_campaigns')->nullOnDelete();
            $table->foreignId('blog_post_id')->nullable()->constrained('blog_posts')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_posts');
    }
};
