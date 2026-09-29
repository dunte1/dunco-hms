<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('safety_incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->enum('incident_type', ['fire', 'near_miss', 'injury', 'exposure', 'equipment_failure', 'chemical', 'other']);
            $table->enum('severity', ['low', 'moderate', 'high', 'critical']);
            $table->text('description');
            $table->string('location');
            $table->string('body_area_affected')->nullable();
            $table->string('injury_type')->nullable();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->dateTime('reported_at');
            $table->boolean('first_aid_given')->default(false);
            $table->boolean('hospital_visit')->default(false);
            $table->enum('status', ['reported', 'investigating', 'resolved', 'closed'])->default('reported');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('safety_incidents');
    }
};
