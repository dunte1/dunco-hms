<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_post_id')->nullable()->constrained('marketing_posts')->nullOnDelete();
            $table->foreignId('campaign_id')->nullable()->constrained('marketing_campaigns')->nullOnDelete();
            $table->string('platform'); // facebook, instagram, twitter, linkedin, blog, website
            $table->string('platform_post_id')->nullable();
            $table->date('analytics_date');
            $table->integer('impressions')->default(0);
            $table->integer('reach')->default(0);
            $table->integer('engagement')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('saves')->default(0);
            $table->json('demographics')->nullable(); // Age, gender, location breakdown
            $table->json('metrics_raw')->nullable(); // Raw data from platform API
            $table->timestamps();
            
            $table->index(['platform', 'analytics_date']);
            $table->index('marketing_post_id');
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_analytics');
    }
};
