<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_appointment_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->datetime('suggested_time');
            $table->integer('confidence_score'); // 0-100
            $table->text('reasoning'); // AI reasoning for suggestion
            $table->json('doctor_availability'); // Available time slots
            $table->json('patient_preferences'); // Patient preferences
            $table->string('status', 20)->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_appointment_suggestions');
    }
};
