<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            $table->integer('pain_score')->nullable()->after('priority_level');
            $table->integer('gcs_score')->nullable()->after('pain_score');
            $table->enum('pregnancy_status', ['yes', 'no', 'unknown'])->default('unknown')->after('gcs_score');
            $table->foreignId('category_id')->nullable()->constrained('triage_categories')->nullOnDelete()->after('pregnancy_status');
        });
    }

    public function down(): void
    {
        Schema::table('triages', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['pain_score', 'gcs_score', 'pregnancy_status', 'category_id']);
        });
    }
};
