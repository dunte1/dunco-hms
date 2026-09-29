<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('anc_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->cascadeOnDelete();
            $table->integer('visit_number');
            $table->date('visit_date');
            $table->integer('gestational_age_weeks');
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->integer('blood_pressure_sys')->nullable();
            $table->integer('blood_pressure_dia')->nullable();
            $table->decimal('hemoglobin', 4, 1)->nullable();
            $table->string('urine_protein', 20)->nullable();
            $table->string('urine_glucose', 20)->nullable();
            $table->decimal('fundal_height', 4, 1)->nullable();
            $table->integer('fetal_heart_rate')->nullable();
            $table->string('presentation', 50)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('visited_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anc_visits');
    }
};
