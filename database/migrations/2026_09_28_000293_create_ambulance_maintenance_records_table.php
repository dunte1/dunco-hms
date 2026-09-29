<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ambulance_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ambulance_id')->constrained('ambulances')->cascadeOnDelete();
            $table->string('maintenance_type', 50); // preventive, corrective, emergency
            $table->text('description');
            $table->date('service_date');
            $table->date('next_service_date')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->string('provider')->nullable();
            $table->string('status', 20)->default('scheduled'); // scheduled, in_progress, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ambulance_maintenance_records');
    }
};
