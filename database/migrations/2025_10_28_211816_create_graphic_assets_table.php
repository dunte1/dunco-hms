<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graphic_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // poster, infographic, social_media, banner
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->json('image_metadata')->nullable(); // Dimensions, format, etc.
            $table->boolean('is_ai_generated')->default(false);
            $table->string('ai_model')->nullable(); // dall-e, stability-ai, etc.
            $table->text('ai_prompt')->nullable();
            $table->string('brand_color_primary')->nullable();
            $table->string('brand_color_secondary')->nullable();
            $table->string('hospital_logo_url')->nullable();
            $table->foreignId('campaign_id')->nullable()->constrained('marketing_campaigns')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->json('tags')->nullable();
            $table->string('status')->default('active'); // active, archived
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graphic_assets');
    }
};
