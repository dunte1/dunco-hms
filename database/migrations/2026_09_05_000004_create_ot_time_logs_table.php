<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ot_time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ot_schedule_id')->constrained('ot_schedules')->cascadeOnDelete();
            $table->enum('event_type', ['patient_arrival', 'prep_start', 'anesthesia_start', 'incision', 'procedure_start', 'procedure_end', 'closure', 'patient_extubation', 'patient_transfer']);
            $table->timestamp('event_time');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ot_time_logs');
    }
};
