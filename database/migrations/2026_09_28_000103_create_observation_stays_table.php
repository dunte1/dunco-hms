<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('observation_stays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_admission_id')->constrained('emergency_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->integer('observation_duration_hours');
            $table->string('observation_purpose');
            $table->text('initial_assessment');
            $table->enum('status', ['active', 'discharged', 'admitted', 'transferred'])->default('active');
            $table->datetime('started_at');
            $table->datetime('ended_at')->nullable();
            $table->string('disposition')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observation_stays');
    }
};
