<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('icu_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('ipd_admission_id')->nullable()->constrained('ipd_admissions')->nullOnDelete();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->string('unit_type', 10)->default('ICU'); // ICU, HDU
            $table->string('admission_from', 20)->default('emergency'); // emergency, ward, theatre, external
            $table->dateTime('admission_datetime');
            $table->text('admission_diagnosis')->nullable();
            $table->foreignId('admitting_doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('status', 20)->default('active'); // active, step_down, transferred, discharged, deceased
            $table->dateTime('discharge_datetime')->nullable();
            $table->string('discharge_destination', 100)->nullable();
            $table->string('discharge_condition', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('icu_admissions');
    }
};
