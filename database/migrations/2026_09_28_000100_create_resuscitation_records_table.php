<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resuscitation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_admission_id')->constrained('emergency_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('presenting_complaint');
            $table->text('initial_assessment');
            $table->string('airway_status', 20);
            $table->string('breathing_status', 20);
            $table->string('circulation_status', 20);
            $table->string('disability_neurological', 20); // GCS score
            $table->text('exposure');
            $table->text('interventions');
            $table->enum('outcome', ['survived', 'died', 'in_transit']);
            $table->datetime('time_of_arrest')->nullable();
            $table->integer('resuscitation_duration_minutes')->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('doctors')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resuscitation_records');
    }
};
