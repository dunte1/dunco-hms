<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('emergency_admissions', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('patient_name'); // For unknown patients
            $table->string('patient_phone')->nullable();
            $table->foreignId('ambulance_id')->nullable()->constrained('ambulances')->nullOnDelete();
            $table->datetime('admission_time');
            $table->string('triage_level', 20); // critical, urgent, semi_urgent, non_urgent
            $table->text('chief_complaint');
            $table->text('vital_signs')->nullable();
            $table->text('initial_assessment')->nullable();
            $table->string('status', 20)->default('active'); // active, discharged, transferred, deceased
            $table->text('discharge_notes')->nullable();
            $table->datetime('discharge_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_admissions');
    }
};
