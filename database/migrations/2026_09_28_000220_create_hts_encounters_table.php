<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hts_encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('encounter_date');
            $table->string('hts_number')->unique();
            $table->boolean('risk_assessment_done')->default(false);
            $table->boolean('consent_given')->default(false);
            $table->enum('test_type', ['hts', 'confirmatory']);
            $table->enum('test_result', ['positive', 'negative', 'inconclusive'])->nullable();
            $table->date('test_date')->nullable();
            $table->foreignId('tested_by')->constrained('users')->cascadeOnDelete();
            $table->boolean('counselled_before')->default(false);
            $table->boolean('counselled_after')->default(false);
            $table->boolean('referral_offered')->default(false);
            $table->enum('status', ['completed', 'referred', 'declined'])->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hts_encounters');
    }
};
