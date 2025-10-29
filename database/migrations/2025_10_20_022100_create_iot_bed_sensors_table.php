<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('iot_bed_sensors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bed_id')->constrained('beds')->cascadeOnDelete();
            $table->string('sensor_id')->unique();
            $table->string('sensor_type', 50); // pressure, temperature, movement, heart_rate
            $table->json('sensor_data'); // Real-time sensor readings
            $table->boolean('is_occupied')->default(false);
            $table->json('vital_signs')->nullable(); // Extracted vital signs
            $table->string('alert_level', 20)->default('normal'); // normal, warning, critical
            $table->text('alerts')->nullable(); // Alert messages
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iot_bed_sensors');
    }
};
