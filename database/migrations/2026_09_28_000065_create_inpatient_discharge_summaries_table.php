<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inpatient_discharge_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ipd_admission_id')->unique()->constrained('ipd_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->text('admission_diagnosis')->nullable();
            $table->text('discharge_diagnosis')->nullable();
            $table->text('procedure_performed')->nullable();
            $table->text('treatment_summary')->nullable();
            $table->string('discharge_condition')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->text('medications_on_discharge')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inpatient_discharge_summaries');
    }
};
