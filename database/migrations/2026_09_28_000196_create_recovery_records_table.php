<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recovery_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ot_schedule_id')->constrained('ot_schedules')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamp('admission_time')->nullable();
            $table->timestamp('discharge_time')->nullable();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->integer('gcs')->nullable();
            $table->json('vital_signs_snapshot')->nullable();
            $table->integer('pain_score')->nullable();
            $table->boolean('nausea_vomiting')->default(false);
            $table->decimal('temperature', 4, 1)->nullable();
            $table->enum('status', ['monitoring', 'recovered', 'transferred'])->default('monitoring');
            $table->boolean('discharge_criteria_met')->default(false);
            $table->text('discharge_notes')->nullable();
            $table->foreignId('nurse_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recovery_records');
    }
};
