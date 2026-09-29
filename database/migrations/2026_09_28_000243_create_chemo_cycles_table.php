<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chemo_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('treatment_plan_id')->constrained('oncology_treatment_plans')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->integer('cycle_number');
            $table->date('scheduled_date');
            $table->date('actual_date')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('scheduled');
            $table->string('delayed_reason')->nullable();
            $table->foreignId('prescribed_by')->constrained('doctors');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chemo_cycles');
    }
};
