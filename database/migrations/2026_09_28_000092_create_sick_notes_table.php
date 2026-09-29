<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sick_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('opd_visit_id')->nullable()->constrained('opd_visits')->nullOnDelete();
            $table->string('sick_note_number')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_off');
            $table->string('diagnosis');
            $table->text('restrictions')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sick_notes');
    }
};
