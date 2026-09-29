<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_access_records', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_registration');
            $table->string('driver_name');
            $table->string('driver_id_number')->nullable();
            $table->string('purpose');
            $table->string('destination');
            $table->datetime('arrival_time')->nullable();
            $table->datetime('departure_time')->nullable();
            $table->string('gate_pass_number')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_access_records');
    }
};
