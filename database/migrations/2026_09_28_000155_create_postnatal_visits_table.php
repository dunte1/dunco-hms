<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('postnatal_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('visit_date');
            $table->integer('visit_day_postpartum');
            $table->integer('blood_pressure_sys')->nullable();
            $table->integer('blood_pressure_dia')->nullable();
            $table->enum('uterine_involution', ['good', 'poor'])->nullable();
            $table->enum('lochia', ['normal', 'abnormal'])->nullable();
            $table->enum('breast_feeding', ['yes', 'no', 'difficulty'])->nullable();
            $table->boolean('family_planning_counselled')->default(false);
            $table->string('family_planning_method', 50)->nullable();
            $table->string('wound_check', 100)->nullable();
            $table->string('mental_health_screening', 100)->nullable();
            $table->text('complications')->nullable();
            $table->foreignId('visited_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postnatal_visits');
    }
};
