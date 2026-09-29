<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('oncology_treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cancer_registration_id')->constrained('cancer_registrations')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->string('plan_name');
            $table->enum('treatment_intent', ['curative', 'palliative', 'adjuvant', 'neoadjuvant']);
            $table->json('modalities');
            $table->date('start_date');
            $table->date('expected_end_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->enum('status', ['proposed', 'active', 'completed', 'discontinued'])->default('proposed');
            $table->foreignId('created_by')->constrained('doctors');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oncology_treatment_plans');
    }
};
