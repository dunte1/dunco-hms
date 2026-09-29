<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('critical_care_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('icu_admission_id')->constrained('icu_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('chart_date');
            $table->integer('hour')->unsigned(); // 0-23
            $table->integer('heart_rate')->nullable();
            $table->integer('blood_pressure_sys')->nullable();
            $table->integer('blood_pressure_dia')->nullable();
            $table->integer('map')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->decimal('spo2', 5, 1)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('gcs_eye')->nullable();
            $table->integer('gcs_verbal')->nullable();
            $table->integer('gcs_motor')->nullable();
            $table->integer('gcs_total')->nullable();
            $table->string('pupil_left', 50)->nullable();
            $table->string('pupil_right', 50)->nullable();
            $table->decimal('urine_output_ml', 8, 1)->nullable();
            $table->decimal('fluid_balance', 8, 1)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('critical_care_charts');
    }
};
