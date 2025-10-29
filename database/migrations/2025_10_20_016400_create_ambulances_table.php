<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ambulances', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->string('driver_name');
            $table->string('driver_phone');
            $table->string('vehicle_type', 50); // basic, advanced, critical_care
            $table->text('equipment_list')->nullable();
            $table->boolean('is_available')->default(true);
            $table->string('status', 20)->default('available'); // available, on_call, maintenance, out_of_service
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulances');
    }
};
