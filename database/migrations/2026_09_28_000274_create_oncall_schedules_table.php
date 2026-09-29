<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('oncall_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('employee_departments')->nullOnDelete();
            $table->date('oncall_date');
            $table->enum('shift_type', ['day', 'night', '24h']);
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['scheduled', 'on_call', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oncall_schedules');
    }
};
