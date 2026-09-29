<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('immunization_schedules');
        Schema::create('immunization_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vaccine_id')->constrained('vaccines')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->integer('dose_number')->default(1);
            $table->date('due_date');
            $table->date('scheduled_date')->nullable();
            $table->enum('status', ['due', 'scheduled', 'completed', 'overdue'])->default('due');
            $table->date('completed_date')->nullable();
            $table->foreignId('administered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('batch_number')->nullable();
            $table->string('site')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immunization_schedules');
    }
};
