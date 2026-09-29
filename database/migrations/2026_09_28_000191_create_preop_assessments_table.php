<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('preop_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('ot_schedule_id')->nullable()->constrained('ot_schedules')->nullOnDelete();
            $table->foreignId('assessor_id')->constrained('doctors')->cascadeOnDelete();
            $table->integer('asa_classification')->default(1);
            $table->enum('airway_assessment', ['easy', 'difficult', 'unknown'])->default('unknown');
            $table->text('comorbidities')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medications')->nullable();
            $table->boolean('npo_status')->default(false);
            $table->integer('fasting_hours')->nullable();
            $table->boolean('airway_teeth_prosthesis')->default(false);
            $table->decimal('weight_kg', 6, 2)->nullable();
            $table->boolean('allergies_confirmed')->default(false);
            $table->text('risks_identified')->nullable();
            $table->text('plan')->nullable();
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preop_assessments');
    }
};
