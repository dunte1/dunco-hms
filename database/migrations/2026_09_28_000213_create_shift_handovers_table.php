<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shift_handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')->constrained('wards')->cascadeOnDelete();
            $table->enum('shift_type', ['day_to_night', 'night_to_day']);
            $table->date('handover_date');
            $table->foreignId('handover_from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('handover_to_user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('patient_count')->default(0);
            $table->integer('critical_patients')->default(0);
            $table->text('pending_tasks')->nullable();
            $table->text('completed_tasks')->nullable();
            $table->text('pending_medications')->nullable();
            $table->text('equipment_issues')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_handovers');
    }
};
