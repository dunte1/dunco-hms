<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_diagnosis_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->json('symptoms'); // Patient symptoms
            $table->json('vital_signs'); // Vital signs data
            $table->json('lab_results'); // Lab test results
            $table->json('suggested_diagnoses'); // AI suggested diagnoses
            $table->integer('confidence_score'); // 0-100
            $table->text('reasoning'); // AI reasoning
            $table->string('status', 20)->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_diagnosis_suggestions');
    }
};
