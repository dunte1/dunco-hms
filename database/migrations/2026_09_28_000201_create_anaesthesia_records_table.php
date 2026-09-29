<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anaesthesia_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ot_schedule_id')->constrained('ot_schedules')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('anaesthetist_id')->constrained('doctors')->cascadeOnDelete();
            $table->enum('anaesthesia_type', ['general', 'regional', 'epidural', 'spinal', 'local', 'sedation']);
            $table->timestamp('induction_time')->nullable();
            $table->timestamp('intubation_time')->nullable();
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->timestamp('extubation_time')->nullable();
            $table->integer('total_duration_minutes')->unsigned()->nullable();
            $table->integer('ebl_ml')->unsigned()->nullable();
            $table->integer('urine_output_ml')->unsigned()->nullable();
            $table->integer('fluids_given_ml')->unsigned()->nullable();
            $table->text('blood_products')->nullable();
            $table->text('complications')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anaesthesia_records');
    }
};
