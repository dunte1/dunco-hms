<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ambulance_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ambulance_id')->constrained('ambulances')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->text('pickup_location');
            $table->text('dropoff_location');
            $table->datetime('departure_time')->nullable();
            $table->datetime('arrival_time')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->string('status', 20)->default('dispatched'); // dispatched, en_route, arrived, completed, cancelled
            $table->string('trip_type', 20); // emergency, transfer, referral, discharge
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_trips');
    }
};
