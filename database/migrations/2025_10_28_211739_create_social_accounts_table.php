<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // facebook, instagram, twitter, linkedin, tiktok
            $table->string('account_name');
            $table->string('account_id')->nullable(); // Platform-specific account ID
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('token_secret')->nullable(); // For OAuth 1.0
            $table->datetime('token_expires_at')->nullable();
            $table->string('status')->default('active'); // active, expired, revoked
            $table->boolean('is_default')->default(false);
            $table->json('metadata')->nullable(); // Profile picture, follower count, etc.
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['platform', 'account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
