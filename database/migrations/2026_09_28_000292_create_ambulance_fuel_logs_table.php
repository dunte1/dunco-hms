<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ambulance_fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ambulance_id')->constrained('ambulances')->cascadeOnDelete();
            $table->date('fill_date');
            $table->decimal('liters', 8, 2);
            $table->decimal('cost', 10, 2);
            $table->integer('odometer_km')->nullable();
            $table->string('station')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_fuel_logs');
    }
};
