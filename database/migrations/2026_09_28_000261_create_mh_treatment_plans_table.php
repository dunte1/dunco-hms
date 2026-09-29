<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mh_treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('mh_assessment_id')->nullable()->constrained('mh_assessments')->nullOnDelete();
            $table->text('diagnosis');
            $table->text('goals');
            $table->text('interventions');
            $table->text('medications')->nullable();
            $table->string('follow_up_frequency')->nullable();
            $table->enum('status', ['active', 'completed', 'discontinued'])->default('active');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mh_treatment_plans');
    }
};
