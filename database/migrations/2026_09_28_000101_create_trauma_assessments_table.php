<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trauma_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_admission_id')->constrained('emergency_admissions')->cascadeOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained('patients')->nullOnDelete();
            $table->string('mechanism_of_injury');
            $table->enum('injury_type', ['blunt', 'penetrating', 'burn', 'misc']);
            $table->text('head_face_neck')->nullable();
            $table->text('chest')->nullable();
            $table->text('abdomen')->nullable();
            $table->text('pelvis')->nullable();
            $table->text('extremities')->nullable();
            $table->text('spinal')->nullable();
            $table->integer('gcs_total');
            $table->string('pupils_left', 50)->nullable();
            $table->string('pupils_right', 50)->nullable();
            $table->json('vital_signs_snapshot')->nullable();
            $table->integer('trauma_score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trauma_assessments');
    }
};
