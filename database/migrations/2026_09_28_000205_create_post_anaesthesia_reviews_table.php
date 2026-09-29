<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_anaesthesia_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anaesthesia_record_id')->constrained('anaesthesia_records')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->timestamp('review_time')->nullable();
            $table->tinyInteger('consciousness_level')->unsigned()->nullable();
            $table->boolean('airway_patent');
            $table->boolean('breathing_spontaneous');
            $table->integer('heart_rate')->unsigned()->nullable();
            $table->integer('blood_pressure_sys')->unsigned()->nullable();
            $table->integer('blood_pressure_dia')->unsigned()->nullable();
            $table->decimal('spo2', 5, 2)->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->tinyInteger('pain_score')->unsigned()->nullable();
            $table->boolean('nausea_vomiting')->default(false);
            $table->tinyInteger('aldrete_score')->unsigned()->nullable();
            $table->boolean('fit_for_discharge')->default(false);
            $table->foreignId('reviewer_id')->constrained('doctors')->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_anaesthesia_reviews');
    }
};
