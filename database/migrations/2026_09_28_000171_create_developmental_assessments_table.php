<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('developmental_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->integer('age_months');
            $table->text('motor_skills');
            $table->text('language_skills');
            $table->text('social_skills');
            $table->text('cognitive_skills');
            $table->text('red_flags')->nullable();
            $table->enum('overall_status', ['on_track', 'delayed', 'critical'])->default('on_track');
            $table->foreignId('assessed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developmental_assessments');
    }
};
