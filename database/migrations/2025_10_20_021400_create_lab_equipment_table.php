<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lab_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('equipment_name');
            $table->string('equipment_type', 50); // analyzer, centrifuge, microscope, etc.
            $table->string('model_number');
            $table->string('serial_number')->unique();
            $table->string('manufacturer');
            $table->string('ip_address')->nullable();
            $table->string('port')->nullable();
            $table->string('connection_type', 50); // tcp, http, serial, usb
            $table->json('configuration')->nullable(); // Equipment-specific config
            $table->boolean('is_connected')->default(false);
            $table->string('status', 20)->default('active'); // active, maintenance, offline
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_equipment');
    }
};
