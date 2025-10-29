<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scheduled_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_post_id')->constrained('marketing_posts')->cascadeOnDelete();
            $table->foreignId('social_account_id')->constrained('social_accounts')->cascadeOnDelete();
            $table->datetime('scheduled_at');
            $table->datetime('posted_at')->nullable();
            $table->string('status')->default('pending'); // pending, posted, failed, cancelled
            $table->string('platform_post_id')->nullable(); // ID returned by social platform
            $table->text('error_message')->nullable();
            $table->integer('retry_count')->default(0);
            $table->json('response_data')->nullable(); // Full API response
            $table->timestamps();
            
            $table->index('scheduled_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scheduled_posts');
    }
};
