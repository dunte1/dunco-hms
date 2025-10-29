<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_records', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable'); // Polymorphic relation to posts, pages, etc.
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable(); // Array of keywords
            $table->text('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->text('schema_markup')->nullable(); // JSON-LD schema
            $table->string('canonical_url')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->integer('keyword_rank')->nullable();
            $table->integer('search_impressions')->default(0);
            $table->integer('search_clicks')->default(0);
            $table->decimal('ctr', 5, 2)->default(0); // Click-through rate
            $table->json('internal_links')->nullable(); // Suggested internal links
            $table->text('ai_keyword_suggestions')->nullable();
            $table->datetime('last_crawled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_records');
    }
};
