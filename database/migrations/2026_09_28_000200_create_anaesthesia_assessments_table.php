<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anaesthesia_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('ot_schedule_id')->constrained('ot_schedules')->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained('doctors')->cascadeOnDelete();
            $table->tinyInteger('asa_classification')->unsigned();
            $table->enum('airway_assessment', ['easy', 'difficult', 'unknown']);
            $table->tinyInteger('mallampati_score')->unsigned()->nullable();
            $table->decimal('mouth_opening_cm', 3, 1)->nullable();
            $table->enum('neck_mobility', ['full', 'restricted']);
            $table->text('previous_anaesthesia_experience')->nullable();
            $table->boolean('airway_teeth_prosthesis')->default(false);
            $table->text('airway_plan');
            $table->text('anaesthesia_plan');
            $table->text('risk_assessment')->nullable();
            $table->timestamp('assessed_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anaesthesia_assessments');
    }
};
