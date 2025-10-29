<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('telemedicine_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->datetime('scheduled_time');
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->string('session_type', 50); // video, audio, chat
            $table->string('platform', 50); // zoom, teams, custom
            $table->string('meeting_url')->nullable();
            $table->string('meeting_id')->nullable();
            $table->string('status', 20)->default('scheduled'); // scheduled, active, completed, cancelled
            $table->text('notes')->nullable();
            $table->json('session_data')->nullable(); // Recording, chat logs, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicine_sessions');
    }
};
