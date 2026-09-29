<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->string('incident_type', 50); // medication_error, near_miss, patient_fall, pressure_injury, device_failure, other
            $table->string('severity', 20); // low, moderate, high, critical
            $table->text('description');
            $table->string('location');
            $table->foreignId('reported_by')->constrained('users');
            $table->datetime('reported_at');
            $table->string('department')->nullable();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('status', 20)->default('reported'); // reported, investigating, resolved, closed
            $table->text('root_cause')->nullable();
            $table->text('corrective_action')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->datetime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
