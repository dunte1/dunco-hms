<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ot_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_number')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('ot_room_id')->constrained('ot_rooms')->cascadeOnDelete();
            $table->foreignId('surgeon_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('anesthetist_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('assistant_doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->foreignId('nurse_id')->nullable()->constrained('nurses')->nullOnDelete();
            $table->string('procedure_name');
            $table->text('procedure_description')->nullable();
            $table->enum('procedure_type', ['elective', 'emergency', 'urgent'])->default('elective');
            $table->enum('anesthesia_type', ['general', 'regional', 'local', 'spinal', 'epidural', 'none'])->default('general');
            $table->date('scheduled_date');
            $table->time('scheduled_start');
            $table->time('scheduled_end')->nullable();
            $table->timestamp('actual_start')->nullable();
            $table->timestamp('actual_end')->nullable();
            $table->enum('status', ['scheduled', 'in_preparation', 'in_progress', 'completed', 'cancelled', 'postponed'])->default('scheduled');
            $table->text('pre_op_notes')->nullable();
            $table->text('intra_op_notes')->nullable();
            $table->text('post_op_notes')->nullable();
            $table->text('complications')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->boolean('consent_signed')->default(false);
            $table->string('consent_form_path')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ot_schedules');
    }
};
