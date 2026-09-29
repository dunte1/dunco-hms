<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('icd_coding_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('ipd_admission_id')->nullable()->constrained('ipd_admissions')->nullOnDelete();
            $table->foreignId('opd_visit_id')->nullable()->constrained('opd_visits')->nullOnDelete();
            $table->string('primary_diagnosis_code');
            $table->string('primary_diagnosis_desc');
            $table->json('secondary_diagnosis_codes')->nullable();
            $table->json('procedure_codes')->nullable();
            $table->enum('coding_status', ['pending', 'reviewed', 'approved'])->default('pending');
            $table->foreignId('coded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('coded_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icd_coding_records');
    }
};
