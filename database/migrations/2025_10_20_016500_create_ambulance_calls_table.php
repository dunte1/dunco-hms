<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ambulance_calls', function (Blueprint $table) {
            $table->id();
            $table->string('call_number')->unique();
            $table->foreignId('ambulance_id')->constrained('ambulances')->cascadeOnDelete();
            $table->string('caller_name');
            $table->string('caller_phone');
            $table->text('pickup_address');
            $table->text('destination_address');
            $table->text('patient_condition');
            $table->datetime('call_time');
            $table->datetime('dispatch_time')->nullable();
            $table->datetime('arrival_time')->nullable();
            $table->datetime('return_time')->nullable();
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('charges', 10, 2)->nullable();
            $table->string('status', 20)->default('pending'); // pending, dispatched, arrived, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_calls');
    }
};
