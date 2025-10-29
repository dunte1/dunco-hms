<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('voice_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->string('audio_file_path');
            $table->text('transcribed_text')->nullable();
            $table->string('note_type', 50); // consultation, diagnosis, treatment, follow_up
            $table->text('notes')->nullable();
            $table->integer('duration_seconds');
            $table->string('status', 20)->default('pending'); // pending, transcribed, reviewed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_notes');
    }
};
