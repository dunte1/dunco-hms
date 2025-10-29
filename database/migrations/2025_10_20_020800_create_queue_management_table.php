<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queue_management', function (Blueprint $table) {
            $table->id();
            $table->string('queue_number')->unique();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('patient_name'); // For walk-in patients
            $table->string('patient_phone')->nullable();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('department', 100);
            $table->string('queue_type', 50); // appointment, walk_in, emergency, follow_up
            $table->string('priority', 20)->default('normal'); // low, normal, high, emergency
            $table->string('status', 20)->default('waiting'); // waiting, called, in_progress, completed, cancelled
            $table->datetime('check_in_time');
            $table->datetime('called_time')->nullable();
            $table->datetime('completed_time')->nullable();
            $table->integer('estimated_wait_time')->nullable(); // in minutes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_management');
    }
};
