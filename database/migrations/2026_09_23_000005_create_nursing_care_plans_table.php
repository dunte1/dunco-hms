<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nursing_care_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('ipd_admission_id')->nullable()->constrained('ipd_admissions')->nullOnDelete();
            $table->foreignId('opd_visit_id')->nullable()->constrained('opd_visits')->nullOnDelete();
            $table->string('care_plan_number', 30)->unique();
            $table->text('nursing_diagnosis')->nullable();
            $table->text('goal')->nullable();
            $table->text('interventions')->nullable();
            $table->text('expected_outcome')->nullable();
            $table->text('actual_outcome')->nullable();
            $table->text('evaluation')->nullable();
            $table->string('status', 30)->default('active'); // active, completed, cancelled
            $table->foreignId('assigned_nurse_id')->nullable()->constrained('nurses')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_care_plans');
    }
};
